<?php

namespace App\Ship\Traits;

trait HasArguments
{
    /** @var  array */
    protected $arguments;

    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    public function getArgument(string $field)
    {
        return data_get($this->arguments, $field);
    }
}
