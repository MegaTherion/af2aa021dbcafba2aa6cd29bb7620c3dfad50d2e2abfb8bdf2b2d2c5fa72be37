<?php
namespace App\Mapper;

use App\Models\Item;

class ItemMapper
{
    public static function itemsLogicToItemsResponse($items) {
        $arr = [];
        foreach ($items as $key => $value) {
            $arr[] = $value;
        }
        return $arr;
    }

    public static function modelToItemResponse(Item $item, $withinsumos = false) {
        $dependencias = [];
        if ($withinsumos) {
            foreach ($item->dependencias as $d) {
                $dependencias[] = [
                    'cantidad' => $d->cantidadComoInsumoDe($item),
                    'item' => self::modelToItemResponse($d, true),
                ];
            }
        }
        return [
            'id' => $item->id,
            'uuid' => $item->uuid,
            'nombre' => $item->nombre,
            'tiempo_produccion' => $item->tiempo_produccion,
            'stock' => (int) $item->stock,
            'insumo_base' => (boolean) $item->insumo_base,
            'dependencias' => $dependencias,
        ];
    }

    public static function collectionToItemsResponse($items) {
        $arr = [];
        foreach ($items as $item) {
            $arr[] = self::modelToItemResponse($item);
        }
        return $arr;
    }
}