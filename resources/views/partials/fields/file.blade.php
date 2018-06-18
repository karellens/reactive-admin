<div class="form-group">
    <label for="{!! $name !!}" class="col-sm-2 control-label">{{ $attrs['title'] }}</label>
    <div class="col-sm-10">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text fa fa-file-image"></span>
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="{!! $name !!}" id="{!! $name !!}">
                <label class="custom-file-label" for="{!! $name !!}">Choose file</label>
            </div>
        </div>
    </div>
    @if(isset($value) && $value && Storage::disk('public')->exists('original/'.$value))
        <div class="col-sm-10">
            <img src="{!! asset('storage/original/'.$value) !!}" alt="" class="img-thumbnail" style="max-width: 200px;">
        </div>
    @endif
</div>