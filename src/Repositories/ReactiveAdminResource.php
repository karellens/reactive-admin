<?php
/**
 * Created by PhpStorm.
 * User: karellen
 * Date: 4/15/18
 * Time: 3:49 PM
 */

namespace Karellens\ReactiveAdmin\Repositories;


use Karellens\ReactiveAdmin\ReactiveAdminServiceProvider;

class ReactiveAdminResource
{
    protected $class;
    protected $query;

    protected $alias;
    protected $title;
    protected $description;

    protected $columns;
    protected $fields;
    protected $filters;

    protected $stack;

    public function __construct($alias, $title, $description='')
    {
        $this->alias = $alias;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias): ReactiveAdminResource
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): ReactiveAdminResource
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription(): string
    {
        return $this->class;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($decription): ReactiveAdminResource
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class): ReactiveAdminResource
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query): ReactiveAdminResource
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param mixed $columns
     */
    public function setColumns($columns): ReactiveAdminResource
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields): ReactiveAdminResource
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param mixed $filters
     */
    public function setFilters($filters): ReactiveAdminResource
    {
        $this->filters = $filters;
        return $this;
    }

    public function addColumn($alias, $title=null): ReactiveAdminResource
    {
        $this->columns[$alias] = new ReactiveAdminColumn($alias, $title);
        $this->stack[] = $this->columns[$alias];
        return $this;
    }

    public function addField($alias, $title): ReactiveAdminResource
    {
        $this->fields[$alias] = new ReactiveAdminField($alias, $title);
        $this->stack[] = $this->fields[$alias];
        return $this;
    }

    public function getEditLink($entity): string
    {
        return url(config('reactiveadmin.uri').'/'.$this->alias.'/'.$entity->id.'/edit');
    }

    public function getCreateLink(): string
    {
        return url(config('reactiveadmin.uri').'/'.$this->alias.'/create');
    }

    public function getColumnTitles(): \Generator
    {
        foreach ($this->getColumns() as $column_alias => $column) {
            yield $column->getTitle();
        }
    }

    public function extractColumns($entities=[])
    {
        foreach ($this->getColumns() as $column_alias => $column) {
            # code...
        }
    }

    public function extractFields($entity=null)
    {
        return null;
    }

    public function __call($method, $args): ReactiveAdminResource
    {
        // apply method to last Column or Field retrieved from stack
        if(count($this->stack) && in_array($method, ['sortable', 'wrapper', 'sizes'])) {
            array_values(array_slice($this->stack, -1))[0]->$method(...$args);
        }
        return $this;
    }
}