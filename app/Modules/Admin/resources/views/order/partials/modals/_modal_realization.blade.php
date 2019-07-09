<!-- Realization modal -->
<div class="modal fade" id="realizationModal" tabindex="-1" role="dialog" aria-labelledby="realizationModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open(array('route' => config('admin.route').'.orders.store')) !!}
            <input type="hidden" name="order_type" value="realization">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="realizationModalLabel">{{ __('Under realization') }}</h4>
            </div>

            <div class="modal-body">
                @include('Admin::order.partials.modals._block_client_info')

                <div class="form-group">
                    <label for="realization_method">{{ __('Select :item', ['item' => __('type')]) }}</label>
                    <select name="realization_method" id="realizationMethod" class="form-control">
                        <option value="{{ \App\Models\Order::REALIZATION_COMMISSION }}">{{ __('Commission') }}</option>
                        <option value="{{ \App\Models\Order::REALIZATION_DISCOUNT }}">{{ __('Discount') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="percent">{{ __('Implementing percentage') }}</label>
                    <div class="input-group">
                        <input type="text" name="realization_percent" id="percent" class="form-control" value="10">
                        <div class="input-group-addon">%</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comment">{{ __('Comment') }}</label>
                    <textarea name="comment" id="comment" rows="4" class="form-control"></textarea>
                </div>

                @include('Admin::order.partials.modals._block_total_sum')
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">{{ __('Print') }}</button>
                    <button type="button" class="btn btn-primary dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" data-toggle="modal">{{ __('QZ thermal printer') }}</a></li>
                        <li><a href="#" data-toggle="modal">{{ __('Thermal printer') }}</a></li>
                    </ul>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Export') }}</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
