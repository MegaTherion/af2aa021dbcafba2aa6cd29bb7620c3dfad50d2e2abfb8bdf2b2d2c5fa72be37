<?php

namespace App\Repositories;

use App\Models\Impresora;
use App\Models\Item;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ImpresoraRepository implements ImpresoraRepositoryInterface
{
    protected $model;

    public function __construct(Impresora $impresora)
    {
        $this->model = $impresora;
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
        if (null == $impresora = $this->model->find($id)) {
            throw new ModelNotFoundException("Impresora not found");
        }

        return $impresora;
    }

    /**
     * Ocupa una impresora por un determinado tiempo para construir
     * un item.
     *
     * @param Item $item
     * @return Impresora|false Retorna false si no hay impresora disponible
     */
    public function ocuparImpresora(Item $item, int $minutes)
    {
        $impresora = $this->model->whereNull('busy_until')->first();
        if (!$impresora) return false;
        $impresora->item_id = $item->id;
        $now = new DateTime();
        $now->modify("+{$minutes} minutes");
        $impresora->busy_until = $now->format('Y-m-d H:i:s');
        $impresora->save();
        return $impresora;
    }

    /**
     * Desocupa la impresora y la guarda
     *
     * @param Impresora $i
     * @return Impresora
     */
    public function desocuparImpresora(Impresora $i)
    {
        $i->busy_until = null;
        $i->item_id = null;
        $i->save();
        return $i;
    }

    /**
     * Termina la produccion incrementando en una unidad el item
     * asociado a la impresora
     *
     * @param Impresora $i
     * @return Impresora
     */
    public function terminarProduccion(Impresora $i) 
    {
        $i->item->stock++;
        $i->item->save();
        $i = $this->desocuparImpresora($i);
        return $i;
    }

    /**
     * Devuelve una instancia de impresora y termina el trabajo si pasó la 
     * cantidad de minutos
     *
     * @param Impresora $i
     * @return Impresora
     */
    public function consultar(Impresora $i)
    {
        if (!$i->busy_until) {
            return $i;
        }
        $now = new DateTime();
        $diff = (new DateTime($i->busy_until))->getTimestamp() - $now->getTimestamp();
        if ($diff <= 0) {
            $i = $this->terminarProduccion($i);
        }
        return $i;
    }
}