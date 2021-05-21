<div class="modal fade" id="modalResendMail" tabindex="-1" role="dialog" aria-labelledby="modalResendMail">
  <div class="modal-dialog modal-sm" role="document">
    <form id="resendMailForm" method="post">
      {{ csrf_field() }}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalResendMailLabel">{{ __('Admin::admin.resend-mail') }}</h4>
        </div>
        <div class="modal-body">
          <label for="additionalMail">{{ __('Send copy to') }}:</label>
          <input type="text" id="additionalMail" name="additional_to" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
