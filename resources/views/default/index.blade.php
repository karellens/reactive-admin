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
                        <h5 class="modal-title" id="confirmLabel">Уверены?</h5>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    </div>
                    <div class="modal-body d-flex">
                        <div class="p-2 flex-fill"><span class="fa fa-question-circle" style="font-size: 2.5rem;"></span></div>
                        <div class="p-2 flex-fill"><p>Вы действительно хотите удалить запись?</p></div>
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

    @endif
@stop