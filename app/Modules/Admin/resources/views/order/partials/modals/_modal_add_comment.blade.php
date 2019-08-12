<div class="modal fade" id="modalAddComment" tabindex="-1" role="dialog" aria-labelledby="modalAddComment">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="AddCommentModalLabel">{{ __('Admin::admin.add-comment') }}</h4>
      </div>
      <div class="modal-body">
        <input type="text" id="addComment" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="addCommentBtn" class="btn btn-primary" disabled="disabled">{{ __('Add') }}</button>
      </div>
    </div>
  </div>
</div>
