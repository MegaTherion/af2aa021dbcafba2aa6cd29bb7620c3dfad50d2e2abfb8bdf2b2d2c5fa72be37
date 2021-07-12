<?php

namespace App\Repositories;

use App\Models\Dependencia;
use App\Models\Item;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DependenciaRepository implements DependenciaRepositoryInterface
{
    protected $model;

    public function __construct(Dependencia $dependencia)
    {
        $this->model = $dependencia;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->model->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        if (null == $dependencia = $this->model->find($id)) {
            throw new ModelNotFoundException("Dependencia not found");
        }

        return $dependencia;
    }

    public function crearDependencia(Item $item, Item $insumo, $cantidad)
    {
        $dependencia = new Dependencia();
        $dependencia->item_id = $item->id;
        $dependencia->insumo_id = $insumo->id;
        $dependencia->cantidad = $cantidad;
        $dependencia->save();
    }
}

