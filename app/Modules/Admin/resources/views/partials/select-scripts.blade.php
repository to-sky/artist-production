<script>
  function getValAsArray($e) {
    var v = $e.val();

    if(!v) return [];

    if(typeof v === "string") return [v];

    return v;
  }

  function loadEventOptions($select, data) {
    $.getJSON('{{ route(config('admin.route') . '.reports.events') }}', data, function(r) {
      var options = r.options || [];
      var html = '';
      var selected = getValAsArray($select);

      options.forEach(function (o) {
        html += '<option value="'+o.id+'" '+(selected.includes(o.id.toString()) ? 'selected' : '' )+'>'+o.name+'</option>';
      });

      $select.html(html).trigger('change');
    });
  }

  jQuery(document).ready(function () {
    var $selects = $('select').select2({
      allowClear: true,
      placeholder: "{{ __('Select value') }}"
    });

    $selects.each(function (i, e) {
      var $select = $(e);

      $select.val('');
      $select.trigger('change');

      if ($select.attr('name') === 'event_ids[]') {
        var $triggers = $('.event_id_trigger, .event_id_trigger_wrap input');

        $triggers.change(function (e) {
          var data = $triggers.serialize();

          loadEventOptions($select, data);
        });

        loadEventOptions($select, $triggers.serialize());
      }
    });
  });
</script>