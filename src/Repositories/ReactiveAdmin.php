<?php
/**
 * Created by PhpStorm.
 * User: karellen
 * Date: 4/15/18
 * Time: 3:49 PM
 */

namespace Karellens\ReactiveAdmin\Repositories;
use Closure;


class ReactiveAdmin
{
    public $resources;

    public function resource($key, $title, Closure $callback)
    {
        $this->resources[$key] = tap(new ReactiveAdminResource($key, $title), $callback);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResource($key)
    {
        return isset($this->resources[$key]) ? $this->resources[$key] : null;
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->resources);
    }

    public function __call($method, $args): ReactiveAdmin
    {
        // apply method to last resource
        if(count($this->resources)) {
            array_values(array_slice($this->resources, -1))[0]->$method(...$args);
        }
        return $this;
    }
}