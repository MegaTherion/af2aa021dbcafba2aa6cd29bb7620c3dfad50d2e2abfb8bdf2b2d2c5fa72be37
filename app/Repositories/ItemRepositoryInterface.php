<?php

namespace App\Repositories;

use App\Models\Item;

interface ItemRepositoryInterface extends RepositoryInterface
{
    public function loadItems(array $array);
    public function calculateTimeProductionAndItems(Item $item, int $cantidad);
    public function executeProduction(Item $item);
    public function decrementStock(Item $item, int $cantidad): Item;
}
