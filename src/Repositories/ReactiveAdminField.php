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
    protected $help;
    protected $default_value;
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
    public function title($title): ReactiveAdminField
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
    public function type($type): ReactiveAdminField
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * @param mixed $help
     * @return ReactiveAdminField
     */
    public function help($help): ReactiveAdminField
    {
        $this->help = $help;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->default_value;
    }

    /**
     * @param mixed $default_value
     * @return ReactiveAdminField
     */
    public function value($default_value): ReactiveAdminField
    {
        $this->default_value = $default_value;
        return $this;
    }

    /**
     * @param Closure $formatter
     * @return ReactiveAdminField
     */
    public function formatter(Closure $formatter): ReactiveAdminField
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