<?php
namespace App\Mapper;

use App\Models\Impresora;
use DateTime;

class ImpresoraMapper
{
    public static function modelToImpresoraResponse(Impresora $impresora)
    {
        $diff = 0;
        if ($impresora->busy_until) {
            $now = new DateTime();
            $diff = (new DateTime($impresora->busy_until))->getTimestamp() - $now->getTimestamp();
        }
        return [
            'id' => $impresora->id,
            'busy' => $impresora->busy_until != null,
            'item' => $impresora->item_id ? $impresora->item->nombre : '',
            'countdown' => $diff > 0 ? $diff : 0,
        ];
    }

    public static function collectionToImpresorasResponse($impresoras) {
        $arr = [];
        foreach ($impresoras as $impresora) {
            $arr[] = self::modelToImpresoraResponse($impresora);
        }
        return $arr;
    }
}