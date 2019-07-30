<div class="modal fade" id="modalInvoice" tabindex="-1" role="dialog" aria-labelledby="modalInvoiceLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalInvoiceLabel">{{ __('Order invoice') }} № <span id="modalTitleId"></span></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Описание</td>
                            <td>Дата создания</td>
                            <td>Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Резервирование заказа</td>
                            <td>2019 07 25 23:43:31</td>
                            <td><button type="button" class="btn btn-xs btn-primary btn-block">Скачать</button></td>
                        </tr>
                        <tr>
                            <td>Оплата заказа</td>
                            <td>2019 07 25 23:43:31</td>
                            <td><button type="button" class="btn btn-xs btn-primary btn-block">Скачать</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
