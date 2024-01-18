<?php

namespace App\Base;

use App\Base\BaseModelInterface;
use Illuminate\Database\Eloquent\Model;

class BaseModel implements BaseModelInterface
{
    private $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all()->toArray();
    }

    public function countRecords(array $data)
    {
        return $this->model->where($data)->get()->count();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findRecord(array $data)
    {
        $record = $this->model->where($data)->first();
        return $record;
    }

    public function findRecords(array $data, $select = ['*'], $limit = null)
    {
        return $this->model->where($data)->select($select)->limit($limit)->get()->toArray();
    }

    public function findRecordsWhereIn(array $data, $column_name, $select = ['*'], $data_where = [])
    {
        return $this->model->whereIn($column_name, $data)->where($data_where)->select($select)->get()->toArray();
    }


    public function findRecordsWhereInAndWhereIn(array $first_data, $first_column_name, array $second_data, $second_column_name)
    {
        return $this->model->whereIn($first_column_name, $first_data)->WhereIn($second_column_name, $second_data)->get()->toArray();
    }

    public function countRecordsWhere(array $data, $groupBy, $selectRaw = 'count(*) as count')
    {
        return $this->model->where($data)->groupBy($groupBy)->selectRaw($selectRaw)->get()->toArray();
    }

    public function countRecordsWhereIn(array $data, $column_name, $groupBy, $selectRaw = 'count(*) as count')
    {
        return $this->model->whereIn($column_name, $data)->groupBy($groupBy)->selectRaw($selectRaw)->get()->toArray();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function updateOrCreate(array $check, array $update)
    {
        return $this->model->updateOrCreate($check, $update);
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update(array $data, $id)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function updateWhere(array $data, array $update)
    {
        return $this->model->where($data)->update($update);
    }

    public function updateWithColumn(array $data, $column_name, $column_value)
    {
        return $this->model->where($column_name, $column_value)->update($data);
    }

    public function delete(array $data)
    {
        return $this->model->where($data)->delete();
    }

    public function destroy($id)
    {
        return $this->model->destory($id);
    }

}