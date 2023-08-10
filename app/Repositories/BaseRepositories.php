<?php

namespace App\Repositories;

abstract class BaseRepositories implements RepositoriesInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    abstract public function getModel();

    public function all()
    {
        return $this->model->orderByDesc('created_at')->paginate(10);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function store(array $data)
    {
       return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        // TODO: Implement update() method.
        $object = $this->model->find($id);
        return $object->update($data);
    }

    public function destroy($id)
    {
        $object = $this->model->find($id);
        if($object)
        {
            return $object->delete();
        }


    }
}
