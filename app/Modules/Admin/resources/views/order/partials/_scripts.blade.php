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

    function inView($el) {
      var elementTop = $el.offset().top;
      var elementBottom = elementTop + $el.outerHeight();

      var viewportTop = $(window).scrollTop();
      var viewportBottom = viewportTop + $(window).height();

      return elementBottom > viewportTop && elementTop < viewportBottom;
    }

    function loadStatictic($el) {
      if (inView($el) && !$el.hasClass('loaded')) {
        $el.load($el.data('url'));
        $el.addClass('loaded');
      }
    }

    // Filter events
    $('#findEvents').click(function () {
       var dateFrom = $('#periodFrom').val();
       var dateTo = $('#periodTo').val();
       var eventName = $('#findByName option:selected').val();

       $('#eventList').load(
           "{{ route('order.eventsList') }}",
           {
             name: eventName,
             start: dateFrom,
             end: dateTo
           },
           function () {
             $('#eventList .event-statistic').each(function() {
               loadStatictic($(this));
             });
           }
       );
    });
    $(window).scroll(function () {
      $('#eventList .event-statistic').each(function() {
        loadStatictic($(this));
      });
    });


     // Select client
    var clientDataPlaceholder = '';
    var clientDataHtml;
    var realizatorCommission = 0;
    $('#datatable tbody tr').click(function () {
        var radio = $(this).find('input[name="client"]');
            radio.prop('checked', true);
        var name = $(this).find('td[data-type="name"]').text();
        var email = $(this).find('td[data-type="email"]').text();
        var phone = $(this).find('td[data-type="phone"]').text();
        var address = $(this).find('input[data-type="address"]').val();
        realizatorCommission = $(this).find('td[data-commission]').data('commission');

        if (!clientDataPlaceholder) clientDataPlaceholder = $('#clientData').html();

        clientDataHtml = $('<div>', {class: 'client-data-container'});
        $('<h4>', {
            class: 'client-name'
        }).append($('<i>', {class: 'fa fa-user'})).append(' ' + name).appendTo(clientDataHtml);

        var clientRow = $('<div>', {class: 'row'}).appendTo(clientDataHtml);

        $('<div>', {class: 'col-md-12'})
            .append($('<i>', {class: 'fa fa-map-marker'}))
            .append($('<span>', {text: ' ' + address})).appendTo(clientRow);

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

    // Clear selected client
    $('#clearClient').click(function () {
      if (!clientDataPlaceholder) clientDataPlaceholder = $('#clientData').html();

      $('#clientData').html(clientDataPlaceholder);
    });

    var eventId;

    // Open widget popup
    $('#eventList').click('.show-widget', function (e) {
        e.preventDefault();

        eventId = $(e.target).data('event-id');

        if (!eventId) return;

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

        return false;
    });

    // add ticket hidden inputs to form
    function addTicketsDataToForm() {
        var tickets = $('tr[data-ticket-id]');
        var ticketsBlock = $('div[data-tickets="content"]');
            ticketsBlock.html('');

        tickets.each(function (i, el) {
            var ticketId = $(el).data('ticket-id');
            var ticketDiscount = parseFloat($(el).find('a[data-target="#discountModal"]').text());
            if (isNaN(ticketDiscount)) ticketDiscount = 0;

            $('<input>', {
                type: 'hidden',
                name: 'tickets[' + ticketId + '][discount]',
                value: ticketDiscount
            }).appendTo(ticketsBlock);
        });

        var mainDiscount = parseFloat($('#mainDiscount').text());
        if (isNaN(mainDiscount)) mainDiscount = 0;
        $('<input>', {
            type: 'hidden',
            name: 'main_discount',
            value: mainDiscount
        }).appendTo(ticketsBlock);

        selectedTickets = tickets.length;

        var ticketsPrice = $('#allTicketsFinalPrice').text();
            ticketsPrice = ticketsPrice === '' ? 0 : ticketsPrice;

        $('span[data-tickets="count"]').text(selectedTickets);
        $('span[data-tickets="final-price"]').text(ticketsPrice);
    }

    var body = $('body');

    // Check selected tickets and add to widget table
    setInterval(function () {
        if ($('div').hasClass('widget-open')) {
            $.get('{{ route("orders.getSelectedTickets") }}', {event_id: eventId}, function (data) {
                var widgetTicketsHeader = $('#widgetTicketsHeader');
                    widgetTicketsHeader.nextAll('tr').remove();

                if ($.isEmptyObject(data)) {
                    var tr = $('<tr>');

                    $('<td>', {
                        colspan: 4,
                        class: 'text-center'
                    }).append($('<small>', {
                        text: '{{ __(":items not selected", ["items" => __("Tickets")]) }}'
                    })).appendTo(tr);

                    widgetTicketsHeader.after(tr);

                    return;
                }

                $.each(data, function (i, ticket) {
                    var tr = $('<tr>');

                    $('<td>', {text: ticket.row}).appendTo(tr);
                    $('<td>', {text: ticket.place}).appendTo(tr);
                    $('<td>', {text: ticket.price}).appendTo(tr);

                    var deleteTd = $('<td>');
                    $('<a>', {
                        'href': '#',
                        'class': 'delete-ticket',
                        'data-ticket-id': ticket.id,
                    }).append($('<i>', {
                        class: 'fa fa-trash text-danger'
                    })).appendTo(deleteTd);

                    deleteTd.appendTo(tr);

                    widgetTicketsHeader.after(tr)
                });
            });
        }

        checkIfRealizationPossible();
    }, 5000);

    // Delete ticket from cart
    body.on('click', '.delete-ticket', function () {
        $.ajax({
            url: '{{ route("tickets.freeTicket") }}',
            type : 'PATCH',
            data: {
                ticket_id: $(this).data('ticket-id')
            }
        });

        var ticketRow = $(this).closest('tr');
        var eventId = ticketRow.data('event-id');

        // Delete table row on tickets table
        // If ticket is last on this event, remove header(event name)
        if (ticketRow.closest('#ticketsTable').length) {
            ticketRow.remove();

            var ticketsTable = $('#ticketsTable');
            var tickets = ticketsTable.find('tr[data-event-id='+ eventId +']').not('tr[data-event="title"]');
            var eventTitle = ticketsTable.find('tr[data-event-id='+ eventId +'][data-event="title"]');

            if (! tickets.length) {
                eventTitle.remove();
            }

            // If ticket is last in order, add empty table row
            if (! $('tbody tr', ticketsTable).length) {
                $('tfoot', ticketsTable).hide();

                $('tbody', ticketsTable).append(
                    $('<tr>').append(
                        $('<td>', {
                            colspan: 7,
                            class: 'text-center'
                        }).append($('<small>', {
                            text: '{{ __(":items not selected", ["items" => __("Tickets")]) }}'
                        }))
                    )
                );
            }
        }

        ticketRow.remove();

        checkIfRealizationPossible();
    });

    // Close widget popup
    body.on('click', '.widget-close-btn', function () {
        $(".widget-content").animate({"left":"1900px"}, 500);

        $.get('{{ route('order.updateTicketsTable') }}', function (data) {
            $('#ticketsTableWrapper').html(data);
        });

        setTimeout(function () {
            $('body').removeClass('modal-open');
            $('.widget-wrapper').remove();
            $('#widgetTicketsHeader').nextAll('tr').remove();
        }, 500);
    });

    // Calculate price for all tickets
    var mainDiscount = 0;
    body.on("DOMSubtreeModified", '#ticketsTable tbody',function() {
        var ticketsPrice = 0;
        $('td[data-price-final]').each(function (i, el) {
            ticketsPrice += parseFloat($(el).data('price-final'));
        });

        var finalPrice = ticketsPrice;
        if (mainDiscount > 0) {
            finalPrice = calcDiscount(ticketsPrice, mainDiscount, 'euro').sum;
        }

        $('#allTicketsPrice').text(ticketsPrice);
        $('#allTicketsFinalPrice').text(finalPrice);
    });

    // Discount modal for tickets
    var targetButton, row, price;
    var allTickets = false;
    $('#discountModal').on('show.bs.modal', function (e) {
        targetButton = $(e.relatedTarget);
        row = targetButton.closest('tr');

        if(targetButton.data('discount') === 'all') {
            allTickets = true;
            price = $('#allTicketsPrice').text()
        } else {
            allTickets = false;
            price = row.find('td[data-price]').data('price');
        }

        $('#discountModalPrice', this).text(price);
    }).on('hide.bs.modal', function () {
        if(allTickets) {
            mainDiscount = parseFloat($('#mainDiscount').text());
        }

        $('#discount', this).val(0);
        $('#discountType', this).val('percent');

        if ($('has-error', this)) {
            $('.has-error').removeClass('has-error');
            $('.text-error').remove();
        }
    });

    // Set discount for ticket or all tickets and close modal
    $('#setDiscount').click(function() {
        var discountBlock = $('#discount');
        var discount = discountBlock.val();
        var type = $('#discountType option:selected').val();
        var finalPrice = calcDiscount(price, discount, type);

        if (finalPrice.sum < 0) {
            var errorText = '{{ __("Invalid value entered") }}';

            if (! discountBlock.parent('div').hasClass('has-error')) {
                discountBlock.parent('div').addClass('has-error').append($('<small>', {
                    text: errorText,
                    class: 'text-red text-error'
                }));
            }

            return false;
        }

        if (allTickets) {
            if (discount > 0) {
                $('#allTicketsFinalPrice').text(finalPrice.sum);
                $('#mainDiscount').text(finalPrice.discountSum);
                $('#discountTypeValue').data('type', type).html('&euro;');
            }
        } else {
            var priceFinalTd = row.find('td[data-price-final]');
            targetButton.text(finalPrice.discountSum);
            priceFinalTd.data('price-final', finalPrice.sum).html(finalPrice.sum + ' &euro;');
        }
    });

    // Calculate discount
    function calcDiscount(currentPrice, discount, type) {
        var finalPrice = currentPrice;
        var discountSum;

        if (type === 'euro') {
            discountSum = discount;
            finalPrice = currentPrice - discount;
        } else if (type === 'percent') {
            discountSum = currentPrice * discount / 100;
            finalPrice = currentPrice - discountSum;
        }

        return {
            sum: Number(finalPrice).toFixed(2),
            discountSum: Number(discountSum).toFixed(2)
        };
    }

    // Change shipping type in modal
    $('#shippingType').change(function () {
        var type = $('option:selected', this).val();
        var paymentTypeInput = $('#paymentType');

        if (type === '{{ \App\Models\Shipping::TYPE_OFFICE }}') {
            paymentTypeInput.attr('value', '{{ __('Payment at the checkout') }}').next('input[type="hidden"]').val('');
        } else {
            paymentTypeInput.attr('value', '{{ __('Bank transfer') }}').next('input[type="hidden"]').val('{{ \App\Models\PaymentMethod::TYPE_DELAY }}');
        }

        // Append/remove address block
        if (type === '{{ \App\Models\Shipping::TYPE_POST }}') {
            var userId = $('#reserveModal input[name="user_id"]').val();
            paymentTypeInput.parent('div').after(createAddressBlock(userId));
        } else {
            $('#shippingAddresses').remove();
            changeShippingPrice(0);
        }
    });

    var selectedTickets = 0;
    // Append client/user to modals
    $('#saleModal, #reserveModal, #realizationModal').on('show.bs.modal', function () {
        addTicketsDataToForm();

        var clientBlock = $('#clientData');
        var userId = $('input[name="user_id"]', clientBlock).val();
        var userName = $('.client-name', clientBlock).text();
            userName = userName ? userName : $('small', clientBlock).text();

        $(this).find('.modal-client-name').text(userName);
        $(this).find('input[name="user_id"]').val(userId);

        var disableButton = false;
        if (! selectedTickets) {
            disableButton = true;
        }

        $('.modal-footer button', this).not('button[data-dismiss="modal"]').attr('disabled', disableButton);
    });

    // Disable/Enable submit buttons if user not selected
    $('#reserveModal, #realizationModal').on('show.bs.modal', function () {
        var user = $(this).find('input[name="user_id"]').val();

        var disableButton = false;
        if (! selectedTickets || ! user.length) {
            disableButton = true;
        }

        $('.modal-footer button', this).not('button[data-dismiss="modal"]').attr('disabled', disableButton);
    });

    // Reset fields after close reserve modal
    $('#reserveModal').on('hide.bs.modal', function () {
        $('#shippingType', this).val(0);

        $('#paymentType', this).attr('value', '{{ __('Bank transfer') }}')
            .next('input[type="hidden"]')
            .val('{{ \App\Models\PaymentMethod::TYPE_DELAY }}');

        $('#shippingAddresses', this).remove();
        $('#comment', this).val('');
        changeShippingPrice(0);
    });

    // Add realization percent from client, on open modal
    $('#realizationModal').on('show.bs.modal', function () {
        $('#realizatorCommission').val(realizatorCommission)
    });

    // Create address block
    function createAddressBlock(user_id) {
        var addressBlock = $('<label>', {
            text: '{{ __('Addresses') }}'
        });

        $.get('{{ route('order.getAddresses') }}', {user_id: user_id}, function (data) {
            if(! data.length) {
                addressBlock.parent().append($('<p>', {
                    text: '{{ __('Client address not set') }}',
                    class: 'text-red'
                }));

                return false;
            }

            $.each(data, function (i, el) {
                addressBlock.parent().append(
                    $('<div>', {class: 'radio'})
                        .append(
                            $('<label>')
                                .append($('<input>', {
                                    type: 'radio',
                                    name: 'address_id',
                                    value: el.id,
                                    checked: i === 0,
                                    'data-price': el.shippingPrice
                                })).append(el.address)
                        )
                );
            });

            var shippingPrice = $('input[name="address_id"]:checked').data('price');
            changeShippingPrice(shippingPrice);
        });

        return $('<div>', {
            id: 'shippingAddresses',
            class: 'form-group'
        }).append(addressBlock);
    }

    // Calculate shipping and tickets price
    function changeShippingPrice(shippingPrice) {
        shippingPrice = parseFloat(shippingPrice);

        var reserveModal = $('#reserveModal');
        var orderPriceBlock = $('span[data-tickets="final-price"]', reserveModal);
        var shippingPriceBlock = $('span[data-tickets="shipping-price"]', reserveModal);
        var orderPrice = parseFloat(orderPriceBlock.text());

        var finalPrice;
        if (shippingPrice > 0) {
            finalPrice = orderPrice + shippingPrice;
            $(shippingPriceBlock).text(shippingPrice);
        } else {
            var shippingPriceOld = parseFloat(shippingPriceBlock.text());
            finalPrice = orderPrice - shippingPriceOld;
            $(shippingPriceBlock).text(0);
        }

        $(orderPriceBlock).text(finalPrice);
    }

    $(document).ready(function () {
      $('#findEvents').click();
    })

    function checkIfRealizationPossible() {
      var tickets = $('tr[data-ticket-id]');
      var realizationPossible = true;
      var alert = $('.a_realization');

      tickets.each(function (i, e) {
        var isLocal = +$(e).data('is-local');

        if (!isLocal) realizationPossible = false;
      });

      $('button[data-target="#realizationModal"]').prop('disabled', !realizationPossible);
      if (realizationPossible) {
        alert.hide();
      } else {
        alert.show();
      }
    }
    checkIfRealizationPossible();
</script>

