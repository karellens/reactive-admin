<?php

namespace Karellens\ReactiveAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see Karellens\ReactiveAdmin\Facades\ReactiveAdmin
 * @package Karellens\ReactiveAdmin
 */

class ReactiveAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Karellens\ReactiveAdmin\Repositories\ReactiveAdmin';
    }
}