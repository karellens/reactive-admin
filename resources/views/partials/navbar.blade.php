
<header id="topnav" class="navbar navbar-midnightblue navbar-fixed-top clearfix" role="banner">

	<span id="trigger-sidebar" class="toolbar-trigger toolbar-icon-bg">
		<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar"><span class="icon-bg"><i class="fa fa-fw fa-bars"></i></span></a>
	</span>

    <a class="navbar-brand" href="{!! url('/') !!}">4clean</a>

    <div class="yamm navbar-left navbar-collapse collapse in">
        <ul class="nav navbar-nav">
            @if($menu = config('reactiveadmin.menu', []))
                @foreach ($menu as $key1 => $value1)
                    @if(is_array($value1))
                        <li class="dropdown" id="widget-classicmenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{!! $key1 !!}<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                @foreach ($value1 as $key2 => $value2)
                                    <li><a href="{!! url(config('reactiveadmin.uri'), $key2) !!}">{!! $value2 !!}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li @if(Request::is(config('reactiveadmin.uri').'/'.$key1))class="active"@endif><a href="{!! url(config('reactiveadmin.uri'), $key1) !!}">{!! $value1 !!}</a></li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>

    <ul class="nav navbar-nav toolbar pull-right">
        <li class="dropdown toolbar-icon-bg">
            <a href="#" id="navbar-links-toggle" data-toggle="collapse" data-target="header>.navbar-collapse">
				<span class="icon-bg">
					<i class="fa fa-fw fa-ellipsis-h"></i>
				</span>
            </a>
        </li>

        <li class="toolbar-icon-bg hidden-xs" id="trigger-fullscreen">
            <a href="#" class="toggle-fullscreen"><span class="icon-bg"><i class="fa fa-fw fa-arrows-alt"></i></span></a>
        </li>


        <li class="dropdown toolbar-icon-bg">
            <a href="#" class="dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="fa fa-fw fa-user"></i></span></a>
            <ul class="dropdown-menu userinfo arrow">
                <li class="dropdown-header">User Menu</li>
                <li><a href="{!! url(config('reactiveadmin.uri').'/users/'.Auth::user()->id.'/edit') !!}"><span class="pull-left">{!! trans('reactiveadmin.edit.title') !!}</span> <i class="pull-right fa fa-cog"></i></a></li>
                <li><a href="{!! url('users/logout') !!}"><span class="pull-left">{!! trans('reactiveadmin.exit') !!}</span> <i class="pull-right fa fa-sign-out"></i></a></li>
            </ul>
        </li>
    </ul>
</header>