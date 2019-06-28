<!-- Clients modal -->
<div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="discountModalLabel">{{ __('Discount') }}</h4>
            </div>
            <div class="modal-body">
                <label for="discount">Введите скидку</label>
                <div class="form-group">
                    <input type="text" id="discount" class="form-control" value="0" style="width: 73%; display: inline-block;">

                    <select name="discount_type" id="discountType" class="form-control" style="width: 25%; display: inline-block;">
                        <option value="percent">%</option>
                        <option value="euro">&euro;</option>
                    </select>
                </div>
                <p class="help-block"><small>Cкидка может быть начислена как процент от стоимости, либо как значение в денежных единицах</small></p>

                <p>Стоимость: <span id="discountModalPrice"></span> &euro;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" id="setDiscount" class="btn btn-primary" data-dismiss="modal">{{ __('Select') }}</button>
            </div>
        </div>
    </div>
</div>
