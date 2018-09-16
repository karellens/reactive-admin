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
    protected $wrapper_callback;
    protected $sortable;

    public function __construct($key, $title)
    {
        $this->sortable = false;
        $this->key = $key;
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return (string)$this->title;
    }

    public function setTitle($title): ReactiveAdminColumn
    {
        $this->title = $title;
        return $this;
    }

    public function sortable($default='asc'): ReactiveAdminColumn
    {
        $this->sortable = true;
        return $this;
    }

    public function wrapper(Closure $wrapper): ReactiveAdminColumn
    {
        $this->wrapper_callback = $wrapper;
        return $this;
    }

    public function wrapperCall($value, $entity)
    {
        $wrapper = $this->wrapper_callback;
        return is_null($wrapper) ? $value : $wrapper($value, $entity);
    }

    public function getOrderLink(): string
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

    public function extractColumn($entity)
    {
        $relation_field = explode('.', $this->key);
        if(count($relation_field) > 1) {
            if(is_a($entity->{$relation_field[1]}, 'Illuminate\Database\Eloquent\Collection')) {
                $value = implode(', ', array_get($one->{$relation_field[1]}->toArray(), $relation_field[1]));
            }
//            elseif(is_subclass_of($entity->{$relation_field[1]}, 'Illuminate\Database\Eloquent\Model')) {     // fuck!
            else {
                $value = $entity->{$relation_field[0]} ? $entity->{$relation_field[0]}->{$relation_field[1]} : '';
            }
        } else {
            $value = $entity->{$this->key};
        }

        return $this->wrapperCall($value, $entity);
    }
}