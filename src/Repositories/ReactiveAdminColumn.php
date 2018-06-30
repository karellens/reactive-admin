<?php
/**
 * Created by PhpStorm.
 * User: karellen
 * Date: 6/29/18
 * Time: 5:13 PM
 */
namespace Karellens\ReactiveAdmin\Repositories;
use Closure;


class ReactiveAdminColumn
{
    protected $key;
    protected $title;
    protected $wrapper;
    protected $sortable;
    protected $column;

    public function __construct($key, $title)
    {
        $this->sortable = false;
        $this->key = $key;
        $this->title = $title;
        $this->column = $this->key;
    }

    public function getTitle()
    {
        return ((bool)$this->title ? $this->title : $this->key);
    }

    public function sortable()
    {
        $this->sortable = true;
        return $this;
    }

    public function wrapper(Closure $wrapper)
    {
        $this->wrapper = $wrapper;
        return $this;
    }

    public function getOrderLink()
    {
        $direction_link = '';

        if((bool)$this->sortable) {
            $orderBy = request()->input('orderBy', []);
            $otherParams = request()->except('orderBy');
            $direction = 'desc';
            $active = false;

            // is current field active
            if(isset($orderBy[$this->key])){
                $active = true;
                $direction = $orderBy[$this->key]=='desc' ? 'asc' : 'desc';
            }

            $direction_link = '<a href="'.request()->url().'?'.http_build_query(array_merge(["orderBy[$this->key]"=>$direction], $otherParams)).'" class="fa '.($direction=='asc' ? 'fa-chevron-up' : 'fa-chevron-down').' '.(!$active ?: 'text-danger').'"></a>';
        }

        return $direction_link;
    }

    public function __toString()
    {

    }
}