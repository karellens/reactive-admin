<?php
/**
 * Created by PhpStorm.
 * User: karellen
 * Date: 6/29/18
 * Time: 5:29 PM
 */

namespace Karellens\ReactiveAdmin\Repositories;


class ReactiveAdminField
{
    protected $key;
    protected $title;
    protected $type;
    protected $formatter_callback;

    public function __construct($key, $title, $type)
    {
        $this->key = $key;
        $this->title = $title;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return ((bool)$this->title ? $this->title : $this->key);
    }

    /**
     * @param $title
     * @return ReactiveAdminField
     */
    public function setTitle($title): ReactiveAdminField
    {
        $this->title = $title;
        return $this;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return ReactiveAdminField
     */
    public function setType($type): ReactiveAdminField
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param Closure $fomatter
     * @return ReactiveAdminField
     */
    public function formatter(Closure $fomatter): ReactiveAdminField
    {
        $this->formatter_callback = $formatter;
        return $this;
    }

    /**
     * @param $value
     * @param $entity
     * @return mixed
     */
    public function formatterCall($value, $entity)
    {
        $formatter = $this->formatter_callback;
        return is_null($formatter) ? $value : $formatter($value, $entity);
    }

    /**
     * @param $method
     * @param $args
     * @return $mixed
     */
    public function __call($method, $args)
    {
        if(starts_with($method, "get")){
            $property = str_after($method, "get");
            return $this->{snake_case($property)};
        } else {
            $property = $method;

            $this->{snake_case($property)} = $args[0];
            return $this;
        }
    }
}