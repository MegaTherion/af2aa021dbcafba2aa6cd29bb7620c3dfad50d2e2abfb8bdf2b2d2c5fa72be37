<?php

namespace App\Repositories;

use App\BusinessLogic\ItemRules;
use App\Exceptions\ImpresorasOcupadasException;
use App\Mapper\ItemMapper;
use App\Models\Item;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemRepository implements ItemRepositoryInterface
{
    protected $model;
    protected $dependenciaRepository;
    protected $impresoraRepository;

    public function __construct(Item $item,
                        DependenciaRepositoryInterface $dependenciaRepository,
                        ImpresoraRepository $impresoraRepository)
    {
        $this->model = $item;
        $this->dependenciaRepository = $dependenciaRepository;
        $this->impresoraRepository = $impresoraRepository;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->model->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        if (null == $item = $this->model->find($id)) {
            throw new ModelNotFoundException("Item not found");
        }

        return $item;
    }

    /**
     * The array must have the following fields (and order):
     * id, 
     * nombre, 
     * insumo_1, 
     * insumo_1_cantidad, 
     * insumo_2, 
     * insumo_2_cantidad, 
     * insumo_3, 
     * insumo_3_cantidad, 
     * insumo_4, 
     * insumo_4_cantidad, 
     * insumo_5, 
     * insumo_5_cantidad,
     * tiempo_produccion,
     * stock,
     * insumo_base,
     *
     * @param array $array
     * @return void
     */
    public function loadItems(array $array) 
    {
        $this->createItems($array);
        $this->createDependencies($array);
    }

    private function createItems(array $array) 
    {
        foreach ($array as $e) {
            $item = new Item();
            $item->uuid = $e[0];
            $item->nombre = $e[1];
            $item->tiempo_produccion = $e[12];
            $item->stock = $e[13];
            $item->insumo_base = $e[14]=='V';
            $item->save();
        }
    }

    private function createDependencies(array $array)
    {
        foreach ($array as $e) {
            $item = Item::where('uuid', $e[0])->first();
            for ($i = 2; $i < 11; $i += 2) {
                $insumo = Item::where('nombre', $e[$i])->first();
                // Si no existe insumo, no tomar en cuenta
                if (!$insumo) break;
                // Si el insumo es cadena vacia o cero, no tomar en cuenta
                if ($e[$i + 1] == '' || $e[$i + 1] == 0) break;
                $this->dependenciaRepository->crearDependencia($item, $insumo, $e[$i + 1]);
            }
        }
    }

    public function calculateTimeProductionAndItems(Item $item, int $cantidad)
    {
        $itemRules = new ItemRules();
        return [
            'time' => $itemRules->calculateTimeProduction($item, $cantidad),
            'items' => ItemMapper::itemsLogicToItemsResponse($itemRules->getItemsToBuild()),
        ];
    }

    public function executeProduction(Item $item)
    {
        $itemRules = new ItemRules();
        $minutes = $itemRules->calculateTimeProduction($item, 1);
        if (!($impresora = $this->impresoraRepository->ocuparImpresora($item, $minutes))) {
            throw new ImpresorasOcupadasException();
        }
        else {
            // "Reservar" los insumos necesarios para esta producción
            $arr = $itemRules->getItemsToBuild();
            foreach ($arr as $e) {
                $it = $this->model->find($e['id']);
                // $diff = $e['stock'] - $e['cantidad'];
                // $it->stock = $diff < 0 ? 0 : $diff;
                $it->stock = $e['stockNuevo'];
                $it->save();
            }
        }
        return $impresora;
    }
}