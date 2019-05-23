<script>
    var citySelect = $("#city_id");
    var buildingSelect = $("#building_id");
    var hallSelect = $("#hall_id");

    // Add select2 to select city
    citySelect.select2({
        language: locale,
        width: '100%',
    }).on('select2:select', function () {
        buildingSelect.val(null).trigger("change");
        hallSelect.val(null).trigger("change");
    });

    // Add select2 with ajax, to select building
    buildingSelect.select2({
        language: locale,
        width: '100%',
        ajax: {
            url: '{!! route('events.getBuildings') !!}',
            data: function () {
                return {
                    city_id: citySelect.val()
                }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    }).on('select2:select', function () {
        hallSelect.val(null).trigger("change");
    });

    // Add select2 with ajax, to select hall
    hallSelect.select2({
        language: locale,
        width: '100%',
        ajax: {
            url: '{!! route('events.getHalls') !!}',
            data: function () {
                return {
                    building_id: buildingSelect.val()
                }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });

    // Image upload
    $('.upload-file').change(function () {
        var uploadFile = $(this);

        var files = !!this.files ? this.files : [];

        // imagePreview file selected, or no FileReader support
        if (!files.length || !window.FileReader) return;

        // only image file
        if (/^image/.test( files[0].type)) {
            var reader = new FileReader();

            // read the local file
            reader.readAsDataURL(files[0]);

            // set image data as background of div
            reader.onloadend = function() {
                uploadFile.closest(".thumbnail-container")
                    .find('.image-preview').css("background-image", "url("+this.result+")");
            }
        }
    });


    var fileUpload = $('.btn-file-upload :file');

    fileUpload.change(function(e) {
        $(this).trigger('fileselect', [e.target.files[0].name]);
    });

    fileUpload.on('fileselect', function(event, label) {
        var input = $(this).parents('#freePass').find(':text');

        if( input.length ) {
            input.val(label);
        }
    });
</script>
