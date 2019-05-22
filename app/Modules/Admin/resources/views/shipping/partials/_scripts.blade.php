<script>
    // Add new shipping zone
    var amountShippingZones = $('#shippingZoneTable tbody tr:not(.hidden, .empty-row)').length;
    $('#addRow').click(function () {
        amountShippingZones++;

        var currentRow = $('.shipping-zone-row');
        var cloneRow = currentRow.first().clone();
        var selectBox = cloneRow.find('select');

        currentRow.parent('tbody').find('tr.empty-row').remove();

        setInputName(cloneRow);

        cloneRow.removeClass('hidden');

        setSelect2(selectBox);

        currentRow.last().after(cloneRow);
    });

    setSelect2('.select2-box');

    // Set select2
    function setSelect2(selector) {
        $(selector).select2({
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
            var name = 'shippingZones['+ amountShippingZones+']['+inputName+']';

            if ($(element).prop('multiple')) {
                name += '[]';
            }

            $(element).attr('name', name);
        });
    }

    // Find empty inputs
    function getEmptyInputs(selector) {
        return $(selector).find('input:not(:hidden, .select2-search__field)').map(function(index, el) {
            if (! $(el).val()) {
                return $(el);
            }
        });
    }

    // Remove empty inputs
    $('a[data-redirect]').click(function() {
        getEmptyInputs('#shippingZoneTable').each(function(index, el) {
            $(el).closest('tr').remove();
        })
    });
</script>
