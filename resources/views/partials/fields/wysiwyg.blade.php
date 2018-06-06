<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200
        });

        $('textarea[name="{{ $name }}"]').parents('form').submit(function (e) {
            $('.summernote').each( function() {
                $(this).val($(this).code());
            });
        })
    });
</script>
<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $attrs['title'] }}</label>
    <div class="col-sm-10">
        <textarea name="{{ $name }}" class="form-control summernote" id="{{ $name }}" placeholder="{{ $attrs['title'] }}" data-type="wysihtml5" rows="6">{{ $value }}</textarea>
    </div>
</div>