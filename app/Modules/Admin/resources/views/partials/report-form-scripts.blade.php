<script>
  jQuery(document).ready(function() {
    var $forms = $('.report_form');

    $forms.each(function (i, e) {

      var $form = $(e);
      var action = $form.attr('action');
      var $target = $($form.data('target'));
      var $container = $($form.data('target-container') || $form.data('target'));

      if (!$target.length) return;

      $form.submit(function (e) {
        e.preventDefault();

        console.log(e);

        $target.load(action, $form.serialize(), function () {
          $container.show();
        });

        return false;
      });

      var $export;
      if ($export = $form.find('.r_export_excel')) {
        var href = $export.data('href');

        $export.click(function () {
          var link = document.createElement('a');
          link.target = '_blank';
          link.href = href + '?' + $form.serialize();
          link.click();
        });
      }
    });
  });
</script>