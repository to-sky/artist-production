<script>

  var $form = $('.t_free_tickets');
  var $refundButton = $('.t_refund');
  var $table = $('.t_return');
  var $templateElement = $table.find('.template');
  var template = $templateElement.html();
  var $dataContainer = $table.find('.data');

  var $code = $('.t_scan_code');
  var $codeTrigger = $('.t_scan_trigger');
  var ticketsList = [];

  $templateElement.remove();

  function scan(code) {
    $.get("{{ route(config('admin.route') . '.tickets.by_barcode') }}?code=" + code)
        .then(function(t) {
          if (!ticketsList.includes(t.ticket_id)) {
            ticketsList.push(t.ticket_id);

            $dataContainer.append(buildRow(t));
            $code.val('');
            $refundButton.prop('disabled', false);
          }
        })
        .catch(function(r) {
          alert(r.responseJSON.message || r.statusText);
        })
    ;
  }

  function buildRow(data) {
    var fillers = {
      barcode: data.barcode,
      ticket_pos: data.ticket_pos,
      event_name: data.event_name,
      hall_name: data.hall_name,
      bookkeeper: data.bookkeeper,
      sale_date: moment(data.sale_date).format('D.M.Y HH:mm'),
      ticket_price: data.ticket_price.toFixed(2),
      ticket_discount: data.ticket_discount.toFixed(2),
      commission: data.commission.toFixed(2),
      return_amount: data.return_amount.toFixed(2),
      ticket_id: data.ticket_id
    };

    var html = template;
    Object.keys(fillers).forEach(function (k) {
      html = html.replace(new RegExp('%' + k + '%', 'g'), fillers[k]);
    });

    return html;
  }

  $code.keydown(function(e) {
    if (e.keyCode === 13) {
      scan($code.val());

      return false;
    }
  });
  $codeTrigger.click(function () {
    scan($code.val());
  });
  $table.click('.r_ticket', function(e) {
    var id = $(e.target).data('id');

    if (id) {
      console.log(id, ticketsList.indexOf(id), ticketsList.splice(ticketsList.indexOf(id) + 1, 1));
      ticketsList = ticketsList.splice(ticketsList.indexOf(id) + 1, 1);
      $(e.target).closest('tr').remove();
      $refundButton.prop('disabled', ticketsList.length <= 0);
    }
  });
  $form.submit(function() {
    $.post($form.attr('action'), $form.serialize(), function() {
      location.reload();
    });

    return false;
  });

</script>