<?php
namespace App\BusinessLogic;

use App\Models\Item;
use Exception;

class ItemRules
{
    
    private $itemsToBuild = [];
    private $calculated;

    public function __construct()
    {
        $this->calculated = false;
    }

    /**
     * Retorna el tiempo de producción de cierta cantidad de un item
     *
     * @param Item $item
     * @param integer $cantidad
     * @return void
     */
    public function calculateTimeProduction(Item $item, int $cantidad): int
    {
        $this->generateItemsToBuild($item, $cantidad);
        $this->calculated = true;
        $sum = 0;
        foreach ($this->itemsToBuild as $item) {
            // $sum += $item['tiempo_produccion'] * $item['cantidad'];
           if ($item['cantidad'] > $item['stock']) {
               $sum += $item['tiempo_produccion'] * ($item['cantidad'] - $item['stock']);
           }
        }
        return $sum;
    }

    /**
     * Obtiene el arreglo con los items que deben producirse. Se debe llamar primero
     * a calculateTimeProduction para generar el arreglo
     *
     * @return array
     */
    public function getItemsToBuild()
    {
        if ($this->calculated) {
            return $this->itemsToBuild;
        }
        else {
            throw new Exception("Regla de negocio, debe calcularse el tiempo de producción antes de obtener los items");
        }
    }

    /**
     * Genera el arreglo conteniendo los items que deben ser construidos
     *
     * @param Item $item
     * @param integer $cantidad
     * @return void
     */
    private function generateItemsToBuild(Item $item, int $cantidad) 
    {
        if ($item->insumo_base) return;

        if (!isset($this->itemsToBuild[$item->id])) {
            $this->itemsToBuild[$item->id] = [
                'id' => $item->id,
                'nombre'=>$item->nombre,
                'stock' => $item->stock,
                'stockNuevo' => $item->stock,
                'tiempo_produccion' => $item->tiempo_produccion,
                'cantidad' => 0,
            ];
        }
        if ($cantidad > $this->itemsToBuild[$item->id]['stockNuevo']) {
            $this->itemsToBuild[$item->id]['cantidad'] += $cantidad - $this->itemsToBuild[$item->id]['stockNuevo'];
            $this->itemsToBuild[$item->id]['stockNuevo'] = 0;
            $cantidadDeps = $cantidad - $this->itemsToBuild[$item->id]['stockNuevo'];
        }
        else {
            $this->itemsToBuild[$item->id]['stockNuevo'] -= $cantidad;
            $cantidadDeps = 0;
        }

        if ($cantidadDeps > 0) {
            foreach ($item->dependencias as $dependencia)
            {
                $this->generateItemsToBuild($dependencia, $cantidadDeps * $dependencia->cantidadComoInsumoDe($item));
            }
        }
    }

    public static function productionTimeWithoutDeps(Item $item)
    {
        return $item->insumo_base ? 0 : $item->tiempo_produccion;
    }
}