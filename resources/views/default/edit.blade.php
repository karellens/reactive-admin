@extends('reactiveadmin::root')

{{-- Content --}}
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>
            <a href="{!! $resource->getListLink() !!}" class="btn btn-back">
                <span class="fa fa-angle-double-left"></span>
            </a>
            {!! trans('reactiveadmin::reactiveadmin.edit.title') !!} {!! trans_choice($resource->getTitle(), 1) !!} {!! str_wrap($resource->getDescription(), ['<small>', '</small>']) !!}
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{!! $resource->getCreateLink() !!}" class="btn btn-success" data-toggle="modalCreate">
                <span class="fa fa-plus-circle"></span> {!! trans('reactiveadmin::reactiveadmin.new') !!}
            </a>
        </div>
    </div>

    @include('reactiveadmin::partials.notifications')

    <!-- Form panes -->
    <form action="{!! $resource->getUpdateLink($row) !!}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
        @csrf
        {!! method_field('PUT') !!}
        <input type="hidden" name="{!! $resource->getAlias().'[id]' !!}" value="{!! $row->id !!}">
        @include('reactiveadmin::partials.edit_ajax')

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-lg btn-primary float-right">{!! trans('reactiveadmin::reactiveadmin.edit.save') !!}</button>
            </div>
        </div>
    </form>
@stop