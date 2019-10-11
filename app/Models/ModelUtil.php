<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-22
 * Time: 17:37
 */

namespace App\Models;

use App\Exceptions\Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\MassAssignmentException;

class ModelUtil
{
    protected $model;

    protected $columns;

    /** @var array */
    protected $attributes;

    private static $_instance;

    private function __construct($model){
        $this->model = new $model;
        $this->columns =  Schema::getColumnListing($this->model->getTable());
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $inputs
     * @return array
     * @desc 获取表单形式
     */
    public function getPassedColumns(array $inputs)
    {
        return array_keys($inputs);
    }

    public function getValidInputs(array $inputs)
    {
        $validInputs = [];

        $inputKeys = array_keys($inputs);
        $modelKeys =  $this->getPreColumns();

        $validKeys = array_intersect($inputKeys, $modelKeys);

        foreach ($validKeys as $key) {
            $validInputs[$key] = $inputs[$key];
        }

        return $validInputs;
    }

    public function getPreColumns()
    {
        return $this->columns;
    }

    public static function getInstance($model)
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance=new self($model);
        }

        return self::$_instance;
    }

    public function create($params)
    {
        $filteredParams = $this->getValidInputs($params);

        try {
            return $this->model->create($filteredParams);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function detail($primaryKey)
    {
        return $this->model->findOrFail($primaryKey);
    }

    public function edit($primaryKey, $params)
    {
        $filteredParams = $this->getValidInputs($params);
        $model = $this->detail($primaryKey);

        return $model->update($filteredParams);
    }

    public function update($attributes)
    {
        $this->model->fill($attributes);

        DB::transaction(function () {
            $this->model->save();
        });
    }
}