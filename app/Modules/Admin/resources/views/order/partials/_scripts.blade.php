<script>
    // Date range
    var dateFormat = 'YYYY-MM-DD';
    var displayedDateFormat = 'D MMM Y';
    var dateFrom, dateTo;
    function addDate(startDate, endDate) {
        dateFrom = startDate.format(dateFormat);
        dateTo = endDate.format(dateFormat);

        $('#period span').html(startDate.format(displayedDateFormat) + ' - ' + endDate.format(displayedDateFormat));

        $('#periodFrom').val(dateFrom);
        $('#periodTo').val(dateTo);
    }

    var startDate = moment();
    var endDate = moment().add(6, 'month').startOf('month');
    var period = $('#period');
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
     $('#findEvents').click(function () {
         var dateFrom = $('#periodFrom').val();
         var dateTo = $('#periodTo').val();
         var eventName = $('#findByName option:selected').val();
         var listEvents = $('#eventList li');

         listEvents.each(function (i, el) {
            var findEventDate = $(this).find('[data-date]').data('date');
            var findPeriod = moment(findEventDate).isBetween(moment(dateFrom, dateFormat), moment(dateTo, dateFormat));
            var findEventName = $(this).find('a.show-widget').text().trim();

            if (findEventName === eventName && findPeriod) {
                $(el).show();
            } else if (eventName === 'all' && findPeriod) {
                $(el).show();
            } else {
                $(el).hide();
            }
        });
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

        $('<div>', {class: 'col-md-5'})
            .append($('<i>', {class: 'fa fa-envelope-o'}))
            .append($('<a>', {
                href: 'mailto:' + email,
                text: ' ' + email
            })).appendTo(clientRow);

        $('<div>', {class: 'col-md-5'})
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

    // Open popup widget
    $('.show-widget').click(function (e) {
        e.preventDefault();

        $.get($(e.target).attr('href'), function (data) {
            $('#mainContent').before(data);

            setTimeout(function () {
                $('body').addClass('modal-open');
                $('.widget-wrapper').removeClass('widget-close').toggleClass('widget-open');
                $(".widget-content").animate({"left":"230px"}, 1000);
            }, 500);
        });
    });

    // Close popup widget
    $('body').on('click', '.widget-close-btn', function () {
        $(".widget-content").animate({"left":"1900px"}, 500);

        setTimeout(function () {
            $('body').removeClass('modal-open');
            $('.widget-wrapper').remove();
        }, 500);
    });
</script>

