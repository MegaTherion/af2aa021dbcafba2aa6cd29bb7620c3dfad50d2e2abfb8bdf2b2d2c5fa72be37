<?php

namespace App\Repositories;

interface ItemRepositoryInterface extends RepositoryInterface
{
    public function loadItems(array $array);
}
