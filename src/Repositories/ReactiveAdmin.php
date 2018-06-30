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
        return $this->resources[$key];
    }

    /**
     * @return mixed
     */
    public function getResource($key)
    {
        return isset($this->resources[$key]) ? $this->resources[$key] : null;
    }
}