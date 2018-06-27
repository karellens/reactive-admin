<?php

namespace Karellens\ReactiveAdmin\Controllers;

use Illuminate\Http\UploadedFile;

use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Intervention\Image\Facades\Image;

class ReactiveAdminController extends Controller
{

    protected $model;
    protected $class_name;
    protected $alias;
    protected $resourceId;
    protected $orderBy;
    protected $perPage;
    protected $filterParams;
    protected $config;
    protected $views;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Route $route)
    {
        // get alias & id
        $this->alias = $route->parameter('alias');
        $this->resourceId = $route->parameter('id');

        if($this->alias && !in_array($this->alias, ['upload', 'files']))
        {
            $this->config = $this->getModelConfig($this->alias);
            $this->model = $this->config['model'];
            $this->class_name = $this->config['class_name'];
        }

        $this->orderBy = (array)request()->input('orderBy');
        $this->perPage = (int)request()->input('perPage', 20);


        $this->filterParams = (array)array_filter(
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

        // define views
        $this->views['create']  = view()->exists('reactiveadmin::'.$this->alias.'.create') ? 'reactiveadmin::'.$this->alias.'.create' : 'reactiveadmin::default.create';
        $this->views['edit']    = view()->exists('reactiveadmin::'.$this->alias.'.edit') ? 'reactiveadmin::'.$this->alias.'.edit' : 'reactiveadmin::default.edit';
        $this->views['index']   = view()->exists('reactiveadmin::'.$this->alias.'.index') ? 'reactiveadmin::'.$this->alias.'.index' : 'reactiveadmin::default.index';
        $this->views['show']    = view()->exists('reactiveadmin::'.$this->alias.'.show') ? 'reactiveadmin::'.$this->alias.'.show' : 'reactiveadmin::default.show';
    }

    protected function getFieldType($field_name)
    {
        return $this->config['edit_fields'][$field_name]['type'];
    }

    protected function getModelConfig($alias)
    {
        $model_name = ucfirst(str_singular($alias));
        return require(app_path('RaaConfig/').$model_name.'.php');
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

    protected function applyFilter(&$model)
    {
        foreach ($this->filterParams as $field => $value)
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
        if(!$this->model)   // show start page
        {
            return view('reactiveadmin::dashboard');
        }
        else
        {
            // apply filter
            $model = $this->applyFilter($this->model);

            return view($this->views['index'])
                ->with('rows', $model->paginate($this->perPage))
                ->with('config', $this->config)
                ->with('alias', $this->alias);
        }
    }

    public function create()
    {
        return view($this->views['create'])
            ->with('config', $this->config)
            ->with('alias', $this->alias);
    }

    public function store()
    {
        $models = [];
        $forms = request()->except(['_token', '_method', 'files']);

        foreach ($forms as $alias => $form) {
            $config = $this->getModelConfig($alias);
            $model = $config['model'];
            $class_name = $config['class_name'];
            // TODO: filter fields
            //        $input = request()->only( array_merge(['password_confirmation'], array_keys($this->config['edit_fields'])) );
            $input = $form;

            $possible_relations = [];
            $own_fields = [];
            // filter only direct fields
            foreach ($input as $k => $v)
            {
                if(is_a($v, 'Illuminate\Http\UploadedFile'))
                {
                    $sizes = $this->config['edit_fields'][$k]['sizes'];
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

            $instance = new $class_name($own_fields);

            try {
                $instance->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->withErrors($e->getMessage())->withInput();
            }

            // one-to-one one-to-many hack
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
        return redirect()->to(config('reactiveadmin.uri').'/'.$this->alias);
    }

    public function show()
    {
        $instance = $this->model->findOrFail((int)$this->resourceId);

        return view($this->views['show'])
            ->with('row', $instance)
            ->with('config', $this->config)
            ->with('alias', $this->alias);
    }

    public function edit()
    {
        $instance = $this->model->findOrFail((int)$this->resourceId);
        return view($this->views['edit'])
            ->with('row', $instance)
            ->with('config', $this->config)
            ->with('alias', $this->alias);
    }

    // update
    public function update()
    {
        $forms = request()->except(['_token', '_method', 'files']);

        foreach ($forms as $alias => $form) {
            $config = $this->getModelConfig($alias);
            $model = $config['model'];
            $class_name = $config['class_name'];
            // TODO: filter fields
            //        $input = request()->only( array_merge(['password_confirmation'], array_keys($this->config['edit_fields'])) );
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
                    $sizes = $config['edit_fields'][$k]['sizes'];
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
            //

            $instance = $model->findOrFail((int)$resourceId);

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

        return redirect()->to(config('reactiveadmin.uri').'/'.$this->alias);
    }

    public function destroy()
    {
        $instance = $this->model->findOrFail((int)$this->resourceId);
        $instance->delete();
        return redirect()->to(config('reactiveadmin.uri').'/'.$this->alias);
    }
}
