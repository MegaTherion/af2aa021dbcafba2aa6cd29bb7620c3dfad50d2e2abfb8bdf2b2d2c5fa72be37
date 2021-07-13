<?php

namespace App\Exceptions;

use Exception;

class NotEnoughStockException extends Exception
{
    public function __construct()
    {
        $this->message = "No hay suficientes existencias en stock";
    }
}
