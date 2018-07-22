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

    protected $permissions = [
        'create'    => true,
        'edit'      => true,
        'destroy'   => true,
        'trash'     => false,
    ];

    protected $alias;
    protected $title;
    protected $description;

    protected $editLinkParams = [];
    protected $createLinkParams = [];

    protected $columns;
    protected $fields;
    protected $filters;

    protected $stack;

    public function __construct($alias, $title, $description=null)
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
        return (string)$this->alias;
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
        return (string)$this->title;
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
        return (string)$this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): ReactiveAdminResource
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
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
     * @param array $permissions
     * @return ReactiveAdminResource
     */
    public function setPermissions(array $permissions): ReactiveAdminResource
    {
        $this->permissions = array_merge($this->permissions, $permissions);
        return $this;
    }

    /**
     * @param mixed $permissions
     * @return bool
     */
    public function can($permissions): bool
    {
        return (bool) !array_diff(
                (array) $permissions,
                array_keys($this->permissions)
            )
            && (bool) array_product(
                array_intersect_key(
                    $this->permissions,
                    array_fill_keys((array) $permissions, true)
                )
            );
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

    /**
     * Columns
     */

    /**
     * @return mixed
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param ReactiveAdminResource
     */
    public function setColumns($columns): ReactiveAdminResource
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @return ReactiveAdminColumn
     */
    public function getColumn($key): ReactiveAdminColumn
    {
        return $this->columns[$key];
    }

    /**
     * @return ReactiveAdminResource
     */
    public function addColumn($key, $title=null): ReactiveAdminResource
    {
        $this->columns[$key] = new ReactiveAdminColumn($key, $title);
        $this->stack[] = $this->columns[$key];
        return $this;
    }


    /**
     * @return \Generator
     */
    public function getColumnTitles(): \Generator
    {
        foreach ($this->getColumns() as  $column) {
            yield $column->getTitle();
        }
    }

    /**
     * @return \Generator
     */
    public function extractColumns($entity): \Generator
    {
        foreach ($this->getColumns() as $key => $column) {
            yield $column->extractColumn($entity);
        }
    }

    /**
     * Fields
     */

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
     * @return ReactiveAdminColumn
     */
    public function getField($key): ReactiveAdminField
    {
        return $this->fields[$key];
    }

    /**
     * @param $key
     * @param $title
     * @param $type
     * @return ReactiveAdminResource
     */
    public function addField($key, $title, $type='string'): ReactiveAdminResource
    {
        $this->fields[$key] = new ReactiveAdminField($key, $title, $type);
        $this->stack[] = $this->fields[$key];
        return $this;
    }

    /**
     * helpful link generators
     */

    /**
     * @return string
     */
    public function getListLink(): string
    {
        return url(config('reactiveadmin.uri').'/'.$this->alias);
    }

    /**
     * @param $entity
     * @return string
     */
    public function getEditLink($entity): string
    {
        $query = http_build_query($this->editLinkParams);
        return url(config('reactiveadmin.uri').'/'.$this->alias.'/'.$entity->id.'/edit'.($query ? '?'.$query : ''));
    }

    /**
     * @param $entity
     * @return string
     */
    public function getUpdateLink($entity): string
    {
        return url(config('reactiveadmin.uri').'/'.$this->alias.'/'.$entity->id);
    }

    /**
     * @return string
     */
    public function getCreateLink(): string
    {
        $query = http_build_query($this->createLinkParams);
        return url(config('reactiveadmin.uri').'/'.$this->alias.'/create').($query ? '?'.$query : '');
    }

    /**
     * @return string
     */
    public function getStoreLink(): string
    {
        return url(config('reactiveadmin.uri').'/'.$this->alias);
    }

    /**
     * @param $entity
     * @return string
     */
    public function getDestroyLink($entity): string
    {
        return url(config('reactiveadmin.uri').'/'.$this->alias.'/'.$entity->id.'/destroy');
    }

    /**
     * @param array $params
     * @return ReactiveAdminResource
     */
    public function setEditLinkParams(array $params): ReactiveAdminResource
    {
        $this->editLinkParams = $params;
        return $this;
    }

    /**
     * @param array $params
     * @return ReactiveAdminResource
     */
    public function setCreateLinkParams(array $params): ReactiveAdminResource
    {
        $this->createLinkParams = $params;
        return $this;
    }

    /**
     * @param $method
     * @param $args
     * @return ReactiveAdminResource
     */
    public function __call($method, $args): ReactiveAdminResource
    {
        // apply method to last Column or Field retrieved from stack
        $proxified_methods = [
            'sortable',
            'wrapper',
            'sizes',
            'options',
            'title',
            'type',
            'help',
            'value',
            'pivotFields',
        ];
        if(count($this->stack) && in_array($method, $proxified_methods)) {
            array_values(array_slice($this->stack, -1))[0]->$method(...$args);
        }
        return $this;
    }
}