<?php

namespace App\Ship\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use LogicException;

/**
 * Trait FindModelToHandle
 * @package App\Ship\Traits
 * @method handleSingle(Model $model)
 */
trait FindModelToHandle
{
    /** @var  Model|null */
    protected $target;

    /** @var Builder|null */
    protected $builder;

    protected $chunk;

    public function __construct($arg)
    {
        if ($arg instanceof Model) {
            $this->target = $arg;
            return;
        }

        if ($arg instanceof Builder) {
            $this->builder = $arg;
            return;
        }

        throw new InvalidArgumentException;
    }

    public function chunk(int $chunk)
    {
        $this->chunk = $chunk;
        return $this;
    }

    public function handle()
    {
        if (!method_exists($this, 'handleSingle')) {
            throw new LogicException('no handle single method specified');
        }

        if ($this->target) {
            return $this->handleSingle($this->target);
        }

        if (
            is_null($this->chunk) or
            $this->builder->count() < $this->chunk
        ) {
            $this->handleMultiple($this->builder->get());
            return;
        }

        $this->builder->chunk($this->chunk, function ($targets) {
            $this->handleMultiple($targets);
        });
    }

    public function handleMultiple($targets)
    {
        foreach ($targets as $target) {
            $this->handleSingle($target);
        }
    }
}