<?php

namespace App\Repositories;

use App\Models\Item;

interface DependenciaRepositoryInterface extends RepositoryInterface
{
    public function crearDependencia(Item $item, Item $insumo, $cantidad);
}
