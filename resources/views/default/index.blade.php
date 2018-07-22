@extends('reactiveadmin::root')

{{-- Content --}}
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>{!! trans_choice($resource->getTitle(), 2) !!} {!! str_wrap($resource->getDescription(), ['<small>', '</small>']) !!}</h1>
        @if($resource->can('create'))
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{!! $resource->getCreateLink() !!}" class="btn btn-success" data-toggle="modalCreate">
                <span class="fa fa-plus-circle"></span> {!! trans('reactiveadmin::reactiveadmin.new') !!}
            </a>
        </div>
        @endif
    </div>

    @include('reactiveadmin::partials.notifications')

    @if(isset($rows) && count($rows))
        {{ method_exists($rows, 'links') ? $rows->links('reactiveadmin::partials.pagination') : '' }}

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <?php $orderBy = request()->input('orderBy', []) ?>
                    @foreach($resource->getColumns() as $column)
                        <th>{!! $column->getTitle() !!} {!! $column->getOrderLink() !!}</th>
                    @endforeach
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $one)
                    <tr data-id="{!! $one->id !!}">
                        @foreach($resource->extractColumns($one) as $value)
                            <td>{!! $value !!}</td>
                        @endforeach
                        <td class="controls d-flex justify-content-end">
                            <div class="btn-group btn-group-sm">
                                @if($resource->can('edit'))
                                <a href="{!! $resource->getEditLink($one) !!}"
                                   class="btn btn-warning"
                                   title="{!! trans('reactiveadmin::reactiveadmin.index.edit') !!}">
                                    <span class="fa fa-pencil-alt"></span>
                                </a>
                                @endif
                                @if($resource->can('destroy'))
                                <a href="#" data-toggle="modal" data-target="#confirmDelete" data-id="{{ $one->id }}" data-action="{!! $resource->getDestroyLink($one) !!}" class="btn btn-danger" data-placement="top" title="{!! trans('reactiveadmin::reactiveadmin.index.delete') !!}">
                                    <span class="fa fa-trash"></span>
                                </a>
                                @endif
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
                            @csrf
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
                        @csrf
                        <input type="hidden" name="_method" value="PUT" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('reactiveadmin.close') !!}</span></button>
                            <h3 class="modal-title" id="modalEditLabel">{!! trans('reactiveadmin.edit.title') !!} <q>{!! trans_choice($resource->getTitle(), 1) !!}</q></h3>
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
                        @csrf
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('reactiveadmin.close') !!}</span></button>
                            <h3 class="modal-title" id="modalEditLabel">{!! trans('reactiveadmin.create.title') !!} <q>{!! trans_choice($resource->getTitle(), 1) !!}</q></h3>
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