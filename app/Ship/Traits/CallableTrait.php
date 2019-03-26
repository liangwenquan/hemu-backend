<?php

namespace App\Ship\Traits;

use InvalidArgumentException;
use function is_array;
use function key;

trait CallableTrait
{
    /**
     * @param string $class
     * @param array $runArguments
     * @param array $methods
     *
     * @return  mixed
     */
    public function call($class, $runArguments = [], $methods = [])
    {
        $action = new $class(...$runArguments);

        if (!method_exists($action, 'handle')) {
            throw new InvalidArgumentException("class does not have handle method");
        }

        // allows calling other methods in the class before calling the main `run` function.
        foreach ($methods as $methodInfo) {
            // if is array means it has arguments
            if (is_array($methodInfo)) {
                $method = key($methodInfo);
                $arguments = $methodInfo[$method];
                if (method_exists($action, $method)) {
                    $action->$method(...$arguments);
                }
            } else {
                // if is string means it's just the function name
                if (method_exists($action, $methodInfo)) {
                    $action->$methodInfo();
                }
            }
        }

        return $action->handle();
    }
}
