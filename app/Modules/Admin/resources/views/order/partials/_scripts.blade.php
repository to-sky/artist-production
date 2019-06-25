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
        $('<h4>', {
            class: 'client-name'
        }).append($('<i>', {class: 'fa fa-user'})).append(' ' + name).appendTo(clientDataHtml);

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
            name: 'user_id',
            value: radio.val()
        }).appendTo(clientDataHtml)
    });

    // Close client modal and add selected client data
    $('#getClient').click(function () {
        $('#clientsModal').modal('hide');
        $('#clientData').html(clientDataHtml);
    });

    var eventId;

    // Open widget popup
    $('.show-widget').click(function (e) {
        e.preventDefault();

        eventId = $(e.target).data('event-id');

        $.get($(e.target).attr('href'), function (data) {
            $('#mainContent').before(data);

            setTimeout(function () {
                var windowHeight = $(window).height();

                $('body').addClass('modal-open');
                $('.widget-wrapper').removeClass('widget-close').toggleClass('widget-open');
                $('#widget iframe').css({
                    "height": windowHeight - 100
                });

                $(".widget-content").animate({
                    "left": $('.main-sidebar').outerWidth(),
                    "width": $('.content-wrapper').width(),
                    "height": windowHeight
                }, 1000);
            }, 500);
        });
    });

    var body = $('body');

    // Check selected tickets
    setInterval(function () {
        if (body.hasClass('modal-open')) {
            $.get('{{ route("orders.getSelectedTickets") }}', {event_id: eventId}, function (data) {
                var html;
                // data.each(function (i, el) {
                //     var tr = $('<tr>');
                //
                //     $('<td>', {'text': el.event.name})
                //     $('<td>', {'text': el.event.name})
                // })

                console.log(data);
            });
        }
    }, 5000);


    // Close widget popup
    body.on('click', '.widget-close-btn', function () {
        $(".widget-content").animate({"left":"1900px"}, 500);

        setTimeout(function () {
            $('body').removeClass('modal-open');
            $('.widget-wrapper').remove();
        }, 500);
    });

    // Change shipping type in modal
    $('#shippingType').change(function () {
        var type = $('option:selected', this).val();
        var paymentTypeInput = $('#paymentType');

        // TODO: set payment_type
        if (type === 'office') {
            paymentTypeInput.attr('value', '{{ __('Payment at the checkout') }}').next('input[type="hidden"]').val(1);
        } else {
            paymentTypeInput.attr('value', '{{ __('Bank transfer') }}').next('input[type="hidden"]').val(2);
        }

        // Append/remove address block
        if (type === 'post') {
            paymentTypeInput.parent('div').after(createAddressBlock());
        } else {
            $('#shippingAddress').closest('div.form-group').remove();
        }
    });

    // Append client/user to modals
    $('#saleModal, #reserveModal, #realizationModal').on('show.bs.modal', function () {
        var clientBlock = $('#clientData');
        var userId = $('input[name="user_id"]', clientBlock).val();
        var userName = $('.client-name', clientBlock).text();
            userName = userName ? userName : $('small', clientBlock).text();

        $(this).find('.modal-client-name').text(userName);
        $(this).find('input[name="user_id"]').val(userId);
    });

    // Disable/Enable submit buttons if user not selected
    $('#reserveModal, #realizationModal').on('show.bs.modal', function () {
        var user = $(this).find('input[name="user_id"]').val();
        var buttonsStatus = (!user.length) ? true : false;

        $('.modal-footer button', this).not('button[data-dismiss="modal"]').attr('disabled', buttonsStatus);
    });

    // Get user addresses and append to modal
    body.on('click', '#anotherAddress', function () {
        // TODO: Send ajax and get all addresses for current user
        $.get(url, {user_id: 3}, function (data) {
            // TODO: append user addresses after address block
        });
    });

    // Create address block
    function createAddressBlock() {
        var inputAddress = $('<div>', {class: 'col-md-9'})
            .append($('<label>', {
                for: 'shippingAddress',
                text: '{{ __('Address') }}'
            }))
            .append($('<input>', {
                type: 'text',
                id: 'shippingAddress',
                class: 'form-control'
            }))
            .append($('<input>', {
                type: 'hidden',
                name: 'address_id',
            }));

        var anotherAddress = $('<div>', {class: 'col-md-3 label-top-offset'})
            .append($('<button>', {
                type: 'button',
                id: 'anotherAddress',
                class: 'btn btn-file-upload pull-right',
                text: '{{ __('Another address') }}'
            }));

        return $('<div>', {class: 'form-group row'}).append(inputAddress).append(anotherAddress);
    }
</script>

