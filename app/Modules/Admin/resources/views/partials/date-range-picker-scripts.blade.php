<script>
  jQuery(document).ready(function ($) {

    var $pickers = $('.date_range_picker');

    var dateFormat = 'YYYY-MM-DD';
    var displayedDateFormat = 'D MMM Y';

    function updatePicker(startDate, endDate, $display, $startInput, $endInput) {
      $display.html(startDate.format(displayedDateFormat) + ' - ' + endDate.format(displayedDateFormat));
      $startInput.val(startDate.format(dateFormat));
      $endInput.val(endDate.format(dateFormat)).change();
    }

    function clearPicker($display, $startInput, $endInput) {
      $display.html('');
      $startInput.val('');
      $endInput.val('').change();
    }

    $pickers.each(function (i, e) {

      var $picker = $(e);
      var initialStart = $picker.data('start') || '';
      var initialEnd = $picker.data('end') || '';
      var name = $picker.data('name');
      var className = $picker.data('class') || '';

      if (!name) return;

      $picker.html(
          '<i class="fa fa-calendar"></i>\n' +
          '<span class="range_display"></span>\n' +
          '<i class="fa fa-caret-down"></i>' +
          '<input type="text" class="'+className+'" style="display: none" name="'+name+'_start" value="'+initialStart+'">' +
          '<input type="text" class="'+className+'" style="display: none" name="'+name+'_end" value="'+initialEnd+'">'
      );

      var $display = $picker.find('.range_display');
      var $startInput = $picker.find('input[name="'+name+'_start"]');
      var $endInput = $picker.find('input[name="'+name+'_end"]');

      var options = {
        ranges: {
          "{{ __('Today') }}": [moment(), moment()],
          "{{ __('Tomorrow') }}": [moment().add(1, 'days'), moment().add(1, 'days')],
          "{{ __('This month') }}": [moment().startOf('month'), moment().endOf('month')],
          "{{ __('Next month') }}": [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
          "{{ __('Next half year') }}": [moment(), moment().add(6, 'month')],
          "{{ __('Next year') }}": [moment(), moment().add(1, 'year')],

        },
        autoUpdateInput: false,
        alwaysShowCalendars: false,
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
      };

      if (initialStart) options.startDate = moment(initialStart, dateFormat);
      if (initialEnd) options.endDate = moment(initialEnd, dateFormat);
      if (!initialStart || !initialEnd) options.locale.cancelLabel = "{{ __('Clear') }}";

      $picker.daterangepicker(options);

      if (initialStart && initialEnd) {
        updatePicker(options.startDate, options.endDate, $display, $startInput, $endInput);
      }

      $picker.on('apply.daterangepicker', function (ev, p) {
        updatePicker(p.startDate, p.endDate, $display, $startInput, $endInput);
      });
      if (!initialStart || !initialEnd) {
        $picker.on('cancel.daterangepicker', function (ev, p) {
          clearPicker($display, $startInput, $endInput);
        });
      }
    });

  });
</script>