@extends('reactiveadmin.root')

{{-- Content --}}
@section('content')
    @if(isset($sidebar))
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            @if(isset($categories))
            @foreach($categories as $one)
            <li{{ Request::url() == 'admin/'.$one->alias ? ' class="active"' : '' }}>
                <a href="{{ URL::to('admin/category/'.$one->alias) }}">{{ $one->title }}</a>
                @if(!empty($one->children))
                    <ul>
                    @foreach($one->children as $two)
                        <li{{ Request::url() == 'admin/'.$one->alias ? ' class="active"' : '' }}>
                            <a href="{{ URL::to('admin/category/'.$two->alias) }}">{{ $two->title }}</a>
                        </li>
                    @endforeach
                    </ul>
                @endif
                </li>
            @endforeach
            @endif
        </ul>
    </div>
    @endif

    <div class="@if(isset($sidebar)) col-sm-offset-3 col-md-offset-2 @endif col-md-10 col-sm-9 main">
        @include('reactiveadmin.partials.notifications')
        <h1 class="page-header">{!! trans('reactiveadmin.edit.title') !!} {!! trans_choice($config['title'], 1) !!}</h1>

        <!-- Form panes -->
        <form action="{!! url(config('reactiveadmin.uri').'/'.$alias.'/'.$row->id) !!}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <input type="hidden" name="_method" value="PUT">
            @include('reactiveadmin.partials.edit_ajax')

            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-lg btn-primary pull-right">{!! trans('reactiveadmin.edit.save') !!}</button>
                </div>
            </div>
        </form>
    </div>
@stop