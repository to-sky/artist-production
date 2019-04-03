<script>
    // Add new shipping zone row
    let elementCount = $('#shippingZoneTable tbody tr').length - 1;
    $('#addRow').click(function () {
        elementCount++;

        let shippingZoneRow = $('.shipping-zone-row');
        let cloneRow = shippingZoneRow.first().clone();
        let selectBox = cloneRow.find('select');

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
            let selected = $(this).val();
            let allWorld = '{{ \App\Models\Country::WORLD }}';

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

            let inputName = $(element).attr('name');
            let name = 'shipping_zones['+ elementCount+']['+inputName+']';

            if ($(element).prop('multiple')) {
                name += '[]';
            }

            $(element).attr('name', name);
        });
    }

    // Modal for remove shipping zone
    let modal = $('#deleteShippingZone');
    modal.on('show.bs.modal', function (e) {
        let target = $(e.relatedTarget);
        let url = target.data('url');
        let tableRow = target.closest('tr');
        let shippingZoneName = tableRow.find('input[name*=name]').val();

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
    $('body').on('click', '.delete-row', function(e) {
        $(this).closest('tr').remove();
    });
</script>
