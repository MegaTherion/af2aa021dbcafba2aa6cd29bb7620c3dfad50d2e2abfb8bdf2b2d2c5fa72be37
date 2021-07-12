<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function dependencias() 
    {
        return $this->belongsToMany(Item::class, 'dependencias', 'item_id', 'insumo_id');
    }

    public function cantidadComoInsumoDe($item)
    {
        $d = Dependencia::where('item_id', $item->id)
                    ->where('insumo_id', $this->id)
                    ->first();
        return $d->cantidad;
    }
}
