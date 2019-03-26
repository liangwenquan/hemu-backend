<?php

namespace App\Ship\Database\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderByField implements Scope
{
    private $field;
    /** @var \Illuminate\Support\Collection */
    private $values;

    public function __construct($field, $values)
    {
        $this->field = $field;
        $this->values = collect($values);
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if ($this->values->isNotEmpty()) {
            $builder->orderByRaw("FIELD ({$this->field}, {$this->values->implode(',')})");
        }
    }
}
