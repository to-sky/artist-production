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
</script>
