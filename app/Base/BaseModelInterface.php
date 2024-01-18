<?php

namespace App\Base;

interface BaseModelInterface
{
    public function all();

    public function findRecord(array $data);

    public function findRecords(array $data);

    public function create(array $data);

    public function updateOrCreate(array $check, array $update);

    public function show($id);

    public function update(array $data, $id);

    public function updateWithColumn(array $data, $column_name, $column_value);

    public function delete(array $data);

    public function destroy($id);
}