<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-22
 * Time: 17:37
 */

namespace App\Models;

use Illuminate\Support\Facades\Schema;

class ModelUtil
{
    protected $model;

    protected $columns;

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

    public function getUserInfo(){}
}