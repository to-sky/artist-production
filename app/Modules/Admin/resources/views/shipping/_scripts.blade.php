<script>
    // Add new shipping zone row
    var elementCount = $('#shippingZoneTable tbody tr').length - 1;
    $('#addRow').click(function () {
        elementCount++;

        var shippingZoneRow = $('.shipping-zone-row');
        var cloneRow = shippingZoneRow.first().clone();
        var selectBox = cloneRow.find('select');

        setInputName(cloneRow);

        cloneRow.removeClass('hidden');

        setSelect2(selectBox);

        shippingZoneRow.last().after(cloneRow);
    });

    setSelect2($('.select2-box'));

    // Set select2
    function setSelect2(el) {
        el.select2({
            width: '100%',
            maximumSelectionSize: 1
        }).on('select2:select', function (e) {
            var selected = $(this).val();
            var allWorld = '{{ \App\Models\Country::WORLD }}';

            if(selected.includes(allWorld)) {
                e.preventDefault();
                $(this).val([allWorld]).trigger("change");
            }
        });
    }

    // Set input/select name
    function setInputName(row) {
        row.find('input, select').each(function(index, element) {
            $(element).prop('disabled', false);

            var inputName = $(element).attr('name');
            var name = 'shipping_zones['+ elementCount+']['+inputName+']';

            if ($(element).prop('multiple')) {
                name += '[]';
            }

            $(element).attr('name', name);
        });
    }

    // Modal for remove shipping zone
    var modal = $('#deleteShippingZone');
    modal.on('show.bs.modal', function (e) {
        var target = $(e.relatedTarget);
        var url = target.data('url');
        var tableRow = target.closest('tr');
        var shippingZoneName = tableRow.find('input[name*=name]').val();

        $(this).find('#modalShippingZoneName').text(shippingZoneName);

        $('.delete-shipping-zone').click(function() {
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function() {
                    modal.modal('hide');
                    tableRow.remove();
                }
            });
        });
    });

    // Remove shipping zone row
    $(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove();
    });
</script>
