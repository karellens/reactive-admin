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
    <script src="{{ asset('vendor/reactiveadmin/summernote/summernote-bs4.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['edit',['undo','redo']],
                    ['headline', ['style']],
                    ['style', ['bold', 'italic', 'underline', 'superscript', 'subscript', 'strikethrough', 'clear']],
                    ['fontface', ['fontname']],
                    ['textsize', ['fontsize']],
                    ['fontclr', ['color']],
                    ['alignment', ['ul', 'ol', 'paragraph', 'lineheight']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link','picture','video','hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']]
                ]
            });
        });
    </script>
@endpush