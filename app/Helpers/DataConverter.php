<?php
namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class DataConverter {
    public static function fromCsvToArray(UploadedFile $file) 
    {
        $filename = date('Y-m-d-H-i-s') . md5(uniqid()) . '.csv';
        $file->storeAs('public/uploads', $filename);
        $arr = [];
        if (($gestor = fopen(__DIR__.'/../../storage/app/public/uploads/'.$filename, "r")) !== FALSE) {
            $fila = 1;
            while (($datos = fgetcsv($gestor, 1500, ",")) !== FALSE) {
                if ($fila++ == 1) continue;

                // TODO: refactorizar hacer datos agregar a arr
                $row = [];
                $numero = count($datos);
                for ($c=0; $c < $numero; $c++) {
                    $row[] = $datos[$c];
                }
                $arr[] = $row;
            }
            fclose($gestor);
        }
        return $arr;
    }
}