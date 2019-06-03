<script>
    // Date range
    var startDate = moment();
    var endDate = moment().add(1, 'month').startOf('month');
    var dateFormat = 'YYYY-MM-DD';
    var displayedDateFormat = 'D MMM Y';
    var period = $('#period');

    var dateFrom, dateTo;
    function addDate(startDate, endDate) {
        dateFrom = startDate.format(dateFormat);
        dateTo = endDate.format(dateFormat);

        $('#period span').html(startDate.format(displayedDateFormat) + ' - ' + endDate.format(displayedDateFormat));
        $('#period ~ input[type="hidden"]').val(dateFrom + ' ' + dateTo);
    }

    period.daterangepicker({
        ranges: {
            "{{ __('Today') }}": [moment(), moment()],
            "{{ __('Tomorrow') }}": [moment().add(1, 'days'), moment().add(1, 'days')],
            "{{ __('This month') }}": [moment().startOf('month'), moment().endOf('month')],
            "{{ __('Next month') }}": [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
            "{{ __('Next half year') }}": [moment(), moment().add(6, 'month')],
            "{{ __('Next year') }}": [moment(), moment().add(1, 'year')],

        },
        alwaysShowCalendars: true,
        startDate: startDate,
        endDate: endDate,
        locale: {
            format: displayedDateFormat,
            applyLabel: "{{ __('Apply') }}",
            cancelLabel: "{{ __('Cancel') }}",
            fromLabel: "{{ __('From') }}",
            toLabel: "{{ __('To') }}",
            customRangeLabel: "{{ __('Custom dates') }}",
            daysOfWeek: [
                "{{ __('Su') }}",
                "{{ __('Mo') }}",
                "{{ __('Tu') }}",
                "{{ __('We') }}",
                "{{ __('Th') }}",
                "{{ __('Fr') }}",
                "{{ __('Sa') }}",
            ],
            monthNames: [
                "{{ __('January') }}",
                "{{ __('February') }}",
                "{{ __('March') }}",
                "{{ __('April') }}",
                "{{ __('May') }}",
                "{{ __('June') }}",
                "{{ __('July') }}",
                "{{ __('August') }}",
                "{{ __('September') }}",
                "{{ __('October') }}",
                "{{ __('November') }}",
                "{{ __('December') }}"
            ],
            "firstDay": 1
        },
    }, addDate);

    addDate(startDate, endDate);


    period.on('hide.daterangepicker', function(ev, picker) {
        dateFrom = picker.startDate.format('YYYY-MM-DD');
        dateTo = picker.endDate.format('YYYY-MM-DD');
    });

    // Filter events
     $('#findEvents').click(function (e) {
         var searchText = $('#search').val();
     });

     // Select client
    var clientDataHtml;
    $('#datatable tbody tr').click(function () {
        var radio = $(this).find('input[name="client"]');
            radio.prop('checked', true);
        var name = $(this).find('td[data-type="name"]').text();
        var email = $(this).find('td[data-type="email"]').text();
        var phone = $(this).find('td[data-type="phone"]').text();

        clientDataHtml = $('<div>', {class: 'client-data-container'});
        $('<h4>').append($('<i>', {class: 'fa fa-user'})).append(' ' + name).appendTo(clientDataHtml);

        var clientRow = $('<div>', {class: 'row'}).appendTo(clientDataHtml);

        $('<div>', {class: 'col-md-4'})
            .append($('<i>', {class: 'fa fa-envelope-o'}))
            .append($('<a>', {
                href: 'mailto:' + email,
                text: ' ' + email
            })).appendTo(clientRow);

        $('<div>', {class: 'col-md-4'})
            .append($('<i>', {class: 'fa fa-phone'}))
            .append(' ' + phone).appendTo(clientRow);

        $('<input>', {
            type: 'hidden',
            name: 'clientId',
            value: radio.val()
        }).appendTo(clientDataHtml)
    });

    // Close client modal and add selected client data
    $('#getClient').click(function () {
        $('#clientsModal').modal('hide');
        $('#clientData').html(clientDataHtml);
    });

    // Show widget
    $('.show-widget').click(function (e) {
        e.preventDefault();

        var eventId = $(this).data('event-id');

        // TODO: open widget
        console.log('widget opened');
    })
</script>

