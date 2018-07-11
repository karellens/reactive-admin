<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <textarea name="{{ $name }}" class="form-control summernote" id="{{ $name }}" placeholder="{{ $label }}" data-type="wysihtml5" rows="6">{{ $value }}</textarea>
    </div>
</div>
@push('styles')
    <link href="{{ asset('vendor/reactiveadmin/summernote/summernote-bs4.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="{{ asset('vendor/reactiveadmin/summernote/summernote-bs4.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var form_data;
            $('.summernote').summernote({
                height: 300,
                callbacks: {
                    onImageUpload: function(files) {
                        // upload image to server and create imgNode...
                        console.log(files);
                        for(var i = files.length - 1; i >= 0; i--) {
                            var self = this;

                            form_data = new FormData();
                            form_data.append('files', files[i]);
                            $.ajax({
                                // Your server script to process the upload
                                url: '/upload',
                                type: 'POST',

                                // Form data
                                data: form_data,

                                // Tell jQuery not to process data or worry about content-type
                                // You *must* include these options!
                                cache: false,
                                contentType: false,
                                processData: false,

                                // Custom XMLHttpRequest
                                xhr: function() {
                                    var myXhr = $.ajaxSettings.xhr();
                                    if (myXhr.upload) {
                                        // For handling the progress of the upload
                                        myXhr.upload.addEventListener('progress', function(e) {
                                            if (e.lengthComputable) {
                                                console.log(e.loaded);
                                                $('progress').attr({
                                                    value: e.loaded,
                                                    max: e.total,
                                                });
                                            }
                                        } , false);
                                    }
                                    return myXhr;
                                }
                            }).done(function(data) {
                                $('.summernote').summernote('editor.insertImage', '/storage/original/'+data);
                                form_data.delete('files');
                            }).fail(function() {
                                alert( "error" );
                            });
                        }
                    }
                },
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