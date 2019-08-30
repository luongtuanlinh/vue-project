<?php

namespace App\Repositories\Eloquent;

class DBRepository {

    /**
    * Eloquent model
    */
    protected $model;

    /**
    * @param $model
    */
    function __construct($model)
    {
        $this->model = $model;
    }

    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }

    public function allOrEmpty($columns = array('*'))
    {
        $data = $this->model->all($columns);
        $data = $data->isNotEmpty() ? $data->toArray() : [];

        return $data;
    }

    public function paginate($per=10)
    {
        return $this->model->orderBy('id', 'desc')->paginate($per);
    }

    public function pluck($column, $key = null, $sortColumn = null, $direction = 'asc') {
        return $this->model->orderBy($sortColumn, $direction)->pluck($column, $key);
    }

    public function findById($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($data, $id, $withTrashed = false)
    {
        $obj = $withTrashed ? $this->model->withTrashed()->findOrFail($id): $this->model->findOrFail($id);
        return $obj->update($data);
    }

    public function destroy($id)
    {
        $obj = $this->model->findOrFail($id);
        return $obj->delete();
    }

    public function findBy($key, $value, $withTrashed = false)
    {
        return !$withTrashed ? $this->model->where($key, $value)->first() : $this->model->where($key, $value)->withTrashed()->first();
    }

    public function findAllBy($key, $value, $columns='*', $isPaginate = false, $per = 10)
    {
        $query = $this->model->where($key, $value)->select($columns)->orderBy('id', 'desc');

        return !$isPaginate ? $query->get() : $query->paginate($per);
    }

    public function destroyByConditions($conditions)
    {
        $query = $this->model;

        foreach($conditions as $key => $value) {
            $query = $query->where($key, $value);
        }

        $obj = $query->first();

        return $obj ? $obj->delete() : false;
    }

    public function restore($id)
    {
        $obj = $this->model->withTrashed()->findOrFail($id);
        return $obj->restore();
    }

    public function exists($key, $value, $withTrashed = false)
    {
        return !$withTrashed ? $this->model->where($key, $value)->exists() : $this->model->where($key, $value)->withTrashed()->exists();
    }

    public function store(array $data, $id=null)
    {
        if ( !$id ) {
            return $this->create($data)->id;
        }
        $this->update($data, $id);
        return $id;
    }
}
