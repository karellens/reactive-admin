<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $attrs['title'] }}</label>
    <div class="col-sm-10">
        <textarea name="{{ $name }}" class="form-control summernote" id="{{ $name }}" placeholder="{{ $attrs['title'] }}" data-type="wysihtml5" rows="6">{{ $value }}</textarea>
    </div>
</div>
@push('styles')
    <link href="{{ asset('vendor/reactiveadmin/summernote/summernote-bs4.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="{{ asset('vendor/reactiveadmin/summernote/summernote-bs4.min.js') }}"></script>
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
@endpush