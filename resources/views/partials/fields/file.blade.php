<div class="form-group">
  <label for="{!! $name !!}" class="col-sm-2 control-label">{{ $attrs['title'] }}</label>
  <div class="col-sm-10">
      <div class="input-group">
          <span class="input-group-addon"><span class="glyphicon glyphicon-file"></span></span>
          <input type="file" name="{!! $name !!}" id="{!! $name !!}" class="form-control">
      </div>

      @if(isset($value) && $value && Storage::disk('public')->has($value))
          <br>
          <img src="{!! asset('upload/'.$value) !!}" alt="" class="img-thumbnail">
      @endif
  </div>
</div>