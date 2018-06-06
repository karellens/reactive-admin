@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h4>Error</h4>
    @foreach ($errors->all() as $error)
      <div>{{ $error }}</div>
    @endforeach
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if ($message = Session::get('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    @if(is_array($message))
      @foreach ($message as $m)
        {{ $m }}
      @endforeach
    @else
      {{ $message }}
    @endif
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if ($message = Session::get('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h4>Error</h4>
    @if(is_array($message))
      @foreach ($message as $m)
        {{ $m }}
      @endforeach
    @else
      {{ $message }}
    @endif
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if ($message = Session::get('warning'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    @if(is_array($message))
      @foreach ($message as $m)
        {{ $m }}
      @endforeach
    @else
      {{ $message }}
    @endif
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if ($message = Session::get('info'))
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    @if(is_array($message))
      @foreach ($message as $m)
        {{ $m }}
      @endforeach
    @else
      {{ $message }}
    @endif
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif