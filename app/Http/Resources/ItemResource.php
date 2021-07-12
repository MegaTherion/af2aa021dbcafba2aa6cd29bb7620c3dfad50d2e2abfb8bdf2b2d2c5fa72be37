<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        $dependencias = [];
        foreach ($this->dependencias as $d) {
            $dependencias[] = [
                'cantidad' => $d->cantidadComoInsumoDe($this),
                'item' => new ItemResource($d),
            ];
        }
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'nombre' => $this->nombre,
            'tiempo_produccion' => $this->tiempo_produccion,
            'stock' => $this->stock,
            'insumo_base' => (boolean) $this->insumo_base,
            'dependencias' => $dependencias
        ];
    }
}