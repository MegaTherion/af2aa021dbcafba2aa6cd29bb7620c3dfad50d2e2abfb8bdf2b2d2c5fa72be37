<?php

namespace App\Repositories;

use App\Models\Impresora;
use App\Models\Item;

interface ImpresoraRepositoryInterface extends RepositoryInterface
{
    public function ocuparImpresora(Item $item, int $minutes);
    public function desocuparImpresora(Impresora $i);
    public function consultar(Impresora $i);
    public function terminarProduccion(Impresora $i);
}
