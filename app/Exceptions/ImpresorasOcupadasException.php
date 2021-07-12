<?php

namespace App\Exceptions;

use Exception;

class ImpresorasOcupadasException extends Exception
{
    public function __construct()
    {
        $this->message = 'Todas las impresoras están ocupadas';
    }
}
