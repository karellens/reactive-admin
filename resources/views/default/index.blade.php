@extends('reactiveadmin::root')

{{-- Content --}}
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>{!! trans_choice($config['title'], 2) !!} @if(isset($config['description']))<small>{!! $config['description'] !!}</small>@endif</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{!! url(config('reactiveadmin.uri').'/'.$alias.'/create') !!}" class="btn btn-success" data-toggle="modalCreate">
                <span class="fa fa-plus-circle"></span> {!! trans('reactiveadmin::reactiveadmin.new') !!}
            </a>
        </div>
    </div>

    @include('reactiveadmin::partials.notifications')

    @if(isset($rows) && count($rows))
        {{ method_exists($rows, 'links') ? $rows->links('reactiveadmin::partials.pagination') : '' }}

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <?php $orderBy = request()->input('orderBy', []) ?>
                    @foreach($config['fields'] as $field => $attrs)
                        <?php $dir = isset($orderBy[$field]) && $orderBy[$field]=='desc' ? 'asc' : 'desc' ?>
                        <th>{!! isset($attrs['title']) ? $attrs['title'] : $field !!} @if(isset($attrs['order']) && $attrs['order'])<a
                                    href="{!! request()->url() !!}?{!! http_build_query(array_merge(["orderBy[$field]"=>$dir], request()->except('orderBy'))) !!}"
                                    class="fa @if($dir=='asc') fa-chevron-up @else fa-chevron-down @endif @if(isset($orderBy[$field])) text-danger @endif"
                            ></a>@endif</th>
                    @endforeach
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $one)
                    <tr data-id="{!! $one->id !!}">
                        @foreach($config['fields'] as $field => $attrs)
                            @if(isset($attrs['field']))
                                @if($one && is_a($one->$field, 'Illuminate\Database\Eloquent\Collection'))
                                    <td>{{ implode(', ', array_fetch($one->$field->toArray(), $attrs['field'])) }}</td>
                                @else
                                    <td>{{ $one->$field ? $one->$field->$attrs['field'] : '' }}</td>
                                @endif
                            @else
                                @if(isset($attrs['wrapper']))
                                    <td>{!! $attrs['wrapper']($one->$field) !!}</td>
                                @else
                                    <td>{!! str_limit($one->$field, 80); !!}</td>
                                @endif
                            @endif
                        @endforeach
                        <td class="controls d-flex justify-content-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{!! url(config('reactiveadmin.uri').'/'.$alias.'/'.$one->id.'/edit') !!}"
                                   class="btn btn-warning"
                                   title="{!! trans('reactiveadmin::reactiveadmin.index.edit') !!}">
                                    <span class="fa fa-pencil-alt"></span>
                                </a>
                                <a href="#" data-toggle="modal" data-target="#confirmDelete" data-id="{{ $one->id }}" data-action="{!! url(config('reactiveadmin.uri').'/'.$alias.'/'.$one->id.'/destroy') !!}" class="btn btn-danger" data-placement="top" title="{!! trans('reactiveadmin::reactiveadmin.index.delete') !!}">
                                    <span class="fa fa-trash"></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ method_exists($rows, 'links') ? $rows->links('reactiveadmin::partials.pagination') : '' }}

        <div id="confirmDelete" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="confirmLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="confirmLabel">Уверены?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-1 col-sm-2 text-center"><span class="glyphicon glyphicon-question-sign" style="font-size: 24px;"></span></div>
                            <div class="col-xs-11 col-sm-10"><p>Вы действительно хотите удалить запись?</p></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-default" tabindex="0" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-danger" tabindex="1">Удалить</button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>


        <!--  Modal edit -->
        <div id="modalEdit" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PUT" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('reactiveadmin.close') !!}</span></button>
                            <h3 class="modal-title" id="modalEditLabel">{!! trans('reactiveadmin.edit.title') !!} <q>{!! trans_choice($config['title'], 1) !!}</q></h3>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">{!! trans('reactiveadmin.edit.cancel') !!}</button>
                            <button type="submit" class="btn btn-lg btn-primary">{!! trans('reactiveadmin.edit.save') !!}</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!--  Modal create -->
        <div id="modalCreate" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        {!! csrf_token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('reactiveadmin.close') !!}</span></button>
                            <h3 class="modal-title" id="modalEditLabel">{!! trans('reactiveadmin.create.title') !!} <q>{!! trans_choice($config['title'], 1) !!}</q></h3>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">{!! trans('reactiveadmin.create.cancel') !!}</button>
                            <button type="submit" class="btn btn-lg btn-primary">{!! trans('reactiveadmin.create.title') !!}</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    @endif
@stop