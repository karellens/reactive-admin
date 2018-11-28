<?php

namespace Karellens\ReactiveAdmin\Controllers;

use Illuminate\Http\UploadedFile;

use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Intervention\Image\Facades\Image;
use Karellens\ReactiveAdmin\Facades\ReactiveAdmin;

class ReactiveAdminController extends Controller
{
    protected $resource;
    protected $key;
    protected $resourceId;
    protected $orderBy;
    protected $perPage;
    protected $filterBy;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Route $route)
    {
        // get alias & id
        $this->key = $route->parameter('alias');
        $this->resourceId = $route->parameter('id');

        $this->orderBy = (array)request()->input('orderBy');
        $this->perPage = (int)request()->input('perPage', 20);


        $this->filterBy = (array)array_filter(
            request()->except(['orderBy', 'perPage', 'page']),
            function($val) {
                if(is_array($val) && isset($val['starts']) && isset($val['ends']))
                {
                    return (bool)$val['starts'] && (bool)$val['ends'];
                }
                else
                {
                    return (bool)$val;
                }
            }
        );
    }

    protected function storePublicFile(UploadedFile $uploadedFile, $dimensions = [])
    {
        $original_dir = public_path('storage/original');
        @mkdir($original_dir, 0755, true);

        $name = md5($uploadedFile->getClientOriginalName() . time()).'.'.pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION);

        $uploadedFile->move($original_dir, $name);

        foreach ($dimensions as $fragment) {
            $thumbnail_dir = public_path('storage/'.implode('x', $fragment));
            @mkdir($thumbnail_dir, 0755, true);

            $img = Image::make($original_dir.'/'.$name);
            $img->fit($fragment[0], $fragment[1], function ($constraint) {
                $constraint->upsize();
            });

            $img->save($thumbnail_dir.'/'.$name);
        }

        return $name;
    }

    protected function applyFilter($model)
    {
        foreach ($this->filterBy as $field => $value)
        {
            if(is_array($value))
            {
                // between
                if(isset($value['starts']) && isset($value['ends']))
                {
                    $model = $model->whereBetween($field, array_values($value));
                }
                // in
                else
                {
                    $model = $model->whereIn($field, array_values($value));
                }

            }
            else
            {
                $model = $model->where($field, $value);
            }
        }

        if(count($this->orderBy))
        {
            $direction = reset($this->orderBy);
            $field = key($this->orderBy);
            $model = $model->orderBy($field, $direction);
        }

        return $model;
    }

    public function upload()
    {
        $files = request()->file('files');
        return $this->storePublicFile($files);
    }

    public function index()
    {
        $resource = ReactiveAdmin::getResource($this->key);
        if(!$resource)   // show start page
        {
            return redirect(config('reactiveadmin.uri'));
        }
        else
        {
            // apply filter
            $resource->setQuery($this->applyFilter($resource->getQuery()));

            return view()
                ->first(['reactiveadmin::'.$this->key.'.index', 'reactiveadmin::default.index'])
                ->with('rows', $resource->getQuery()->paginate($this->perPage))
                ->with('resource', $resource)
                ->with('key', $this->key);
        }
    }

    public function create()
    {
        $resource = ReactiveAdmin::getResource($this->key);

        abort_unless($resource->can('create'), 403);
        abort_unless($resource, 404);

        return view()
            ->first(['reactiveadmin::'.$this->key.'.create', 'reactiveadmin::default.create'])
            ->with('resource', $resource);
    }

    public function store()
    {
        $models = [];
        $forms = request()->only(array_keys(ReactiveAdmin::getResourcesLabels()));

        foreach ($forms as $alias => $form) {
            $resource = ReactiveAdmin::getResource($alias);

            // WARNING! may cause error or unexpected behavior in case of differrent permissions for individual models
            abort_unless($resource->can('create'), 403);
            abort_unless($resource, 404);

            $input = $form;

            $possible_relations = [];
            $own_fields = [];

            // filter only direct fields
            foreach ($input as $k => $v)
            {
                if(is_a($v, 'Illuminate\Http\UploadedFile'))
                {
                    $sizes = $resource->getField($k)->getSizes();
                    $own_fields[$k] = $this->storePublicFile($v, $sizes);
                }
                elseif (!is_array($v))
                {
                    $own_fields[$k] = $v;
                }
                else
                {
                    //cleanup
                    $temp = [];
                    foreach ($v as $id => &$item) {
                        if(isset($item['checked'])){
                            $temp[$id] = $item;
                            unset($temp[$id]['checked']);
                        }
                    }
                    $possible_relations[$k] = $temp;
                }
            }

            // password ugly hack
            if(isset($own_fields['password']) && isset($own_fields['password_confirmation']) && $own_fields['password']){
                $own_fields['password'] = bcrypt($own_fields['password']);
                $own_fields['password_confirmation'] = bcrypt($own_fields['password_confirmation']);
            }

            $class_name = $resource->getClass();
            $instance = new $class_name($own_fields);

            try {
                $instance->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->withErrors($e->getMessage())->withInput();
            }

            // Associate with previous model. one-to-one one-to-many hack
            if(count($models)) {
                $previous_model = array_values(array_slice($models, -1))[0];
                $previous_model_name = array_keys(array_slice($models, -1))[0];
                $instance->$previous_model_name()->associate($previous_model);
                $instance->save();
            }
            //

            foreach ($possible_relations as $k=>$v) {
                $instance->$k()->detach();
                $instance->$k()->attach($v);
            }

            $models[str_singular($alias)] = $instance;
        }

        // redirect back
        return redirect()->to($resource->getListLink($this->key));
    }

    public function show()
    {
        $resource = ReactiveAdmin::getResource($this->key);
//        abort_unless($resource->can('show'), 403);
        abort_unless($resource, 404);

        return view()
            ->first(['reactiveadmin::'.$this->key.'.show', 'reactiveadmin::default.show'])
            ->with('row', $resource->getQuery()->findOrFail((int)$this->resourceId))
            ->with('alias', $resource);
    }

    public function edit()
    {
        $resource = ReactiveAdmin::getResource($this->key);
        abort_unless($resource->can('edit'), 403);
        abort_unless($resource, 404);

        return view()
            ->first(['reactiveadmin::'.$this->key.'.edit', 'reactiveadmin::default.edit'])
            ->with('row', $resource->getQuery()->findOrFail((int)$this->resourceId))
            ->with('resource', $resource);
    }

    // update
    public function update()
    {
        $forms = request()->only(array_keys(ReactiveAdmin::getResourcesLabels()));

        foreach ($forms as $alias => $form) {
            $resource = ReactiveAdmin::getResource($alias);

            // WARNING! may cause error or unexpected behavior in case of differrent permissions for individual models
            abort_unless($resource->can('edit'), 403);
            abort_unless($resource, 404);

            $input = $form;
            $resourceId = $input['id'];
            unset($input['id']);

            $possible_relations = [];
            $own_fields = [];

            // filter only direct fields
            foreach ($input as $k => $v)
            {
                if(is_a($v, 'Illuminate\Http\UploadedFile'))
                {
                    $sizes = $resource->getField($k)->getSizes();
                    $own_fields[$k] = $this->storePublicFile($v, $sizes);
                }
                elseif (!is_array($v))
                {
                    $own_fields[$k] = $v;
                }
                else
                {
                    //cleanup
                    $temp = [];
                    foreach ($v as $id => &$item) {
                        if(isset($item['checked'])){
                            $temp[$id] = $item;
                            unset($temp[$id]['checked']);
                        }
                    }
                    $possible_relations[$k] = $temp;
                }
            }

            // password ugly hack
            if(isset($own_fields['password']) && strlen($own_fields['password']) && $own_fields['password']==$own_fields['password_confirmation']){
                $own_fields['password'] = bcrypt($own_fields['password']);
                unset($own_fields['password_confirmation']);
            }
            else {
                unset($own_fields['password']);
                unset($own_fields['password_confirmation']);
            }

            // is SoftDeletes
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($resource->getClass()))) {
                $instance = app()->make($resource->getClass())->withTrashed()->findOrFail((int)$resourceId);
            } else {
                $instance = app()->make($resource->getClass())->findOrFail((int)$resourceId);
            }

            try {
                $instance->update($own_fields);
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->withErrors($e->getMessage())->withInput();
            }

            foreach ($possible_relations as $k=>$v) {
                $instance->$k()->detach();
                $instance->$k()->attach($v);
            }
        }

//        return redirect()->to($resource->getListLink());
        return redirect()->back();
    }

    public function trash()
    {
        $resource = ReactiveAdmin::getResource($this->key);
        abort_unless($resource->can('trash'), 403);
        abort_unless($resource, 404);

        $instance = $resource->getQuery()->findOrFail((int)$this->resourceId);
        $instance->delete();

        return redirect()->to($resource->getListLink());
    }

    public function restore()
    {
        $resource = ReactiveAdmin::getResource($this->key);
        abort_unless($resource->can('trash'), 403);
        abort_unless($resource, 404);

        $instance = $resource->getQuery()->findOrFail((int)$this->resourceId);
        $instance->restore();

        return redirect()->to($resource->getListLink());
    }

    public function destroy()
    {
        $resource = ReactiveAdmin::getResource($this->key);
        abort_unless($resource->can('destroy'), 403);
        abort_unless($resource, 404);

        $instance = $resource->getQuery()->findOrFail((int)$this->resourceId);
        $instance->forceDelete();

        return redirect()->to($resource->getListLink());
    }
}
