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
    public $deferred_calls;

    public function resource($key, $title, Closure $callback)
    {
        $this->resources[$key] = new ReactiveAdminResource($key, $title);
        $this->deferred_calls[$key] = $callback;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResource($key)
    {
        return isset($this->resources[$key]) ? tap($this->resources[$key], $this->deferred_calls[$key]) : null;
    }

    /**
     * @return array
     */
    public function getResourcesLabels(): array
    {
        return array_combine(
            array_keys($this->resources),
            array_map(function($r) { return $r->getTitle(); }, $this->resources)
        );
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