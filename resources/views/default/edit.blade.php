@extends('reactiveadmin::root')

{{-- Content --}}
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>{!! trans('reactiveadmin::reactiveadmin.edit.title') !!} {!! trans_choice($config['title'], 1) !!} @if(isset($config['description']))<small>{!! $config['description'] !!}</small>@endif</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{!! url(config('reactiveadmin.uri').'/'.$alias.'/create') !!}" class="btn btn-success" data-toggle="modalCreate">
                <span class="fa fa-plus-circle"></span> {!! trans('reactiveadmin::reactiveadmin.new') !!}
            </a>
        </div>
    </div>

    @include('reactiveadmin::partials.notifications')


    <!-- Form panes -->
    <form action="{!! url(config('reactiveadmin.uri').'/'.$alias.'/'.$row->id) !!}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="PUT">
        @include('reactiveadmin::partials.edit_ajax')

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-lg btn-primary float-right">{!! trans('reactiveadmin::reactiveadmin.edit.save') !!}</button>
            </div>
        </div>
    </form>
@stop