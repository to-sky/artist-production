<div class="modal fade" id="deleteItem" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4>
                    {!! __('Are you sure you want to delete :item?', ['item' => '<span id="modalItemName" class="label label-default"></span>']) !!}
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-item">
                    {{ __('Admin::admin.delete') }}
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    {{ __('Admin::admin.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
