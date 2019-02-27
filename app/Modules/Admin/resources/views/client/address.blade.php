<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h3 class="modal-title">{{ trans('Адрес клиента') }}</h3>
</div>

<div class="modal-body">
    {!! Form::open(array('route' => config('admin.route').'.clients.store')) !!}
    <table id="address-table">
        <th width="2%"></th>
        <th width="13%">{{ __('First name') }}</th>
        <th width="13%">{{ __('Last name') }}</th>
        <th width="18%">{{ __('Street') }}</th>
        <th width="7%">{{ __('House') }}</th>
        <th width="7%">{{ __('Apart.') }}</th>
        <th width="12%">{{ __('Post code') }}</th>
        <th width="12%">{{ __('City') }}</th>
        <th width="13%">{{ __('Country') }}</th>
        <th width="3%">
            <button type="button" id="add-address" class="btn btn-xs btn-success">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </th>
        @if (empty(old('Addresses', $addresses)))
            <tr id="no-data" align="center"><td colspan="10">{{ trans('Нет данных') }}</td></tr>
        @else
            @foreach(old('Addresses', $addresses) as $i => $address)
                <tr class="address-row @if(empty($address['id'])) {{ 'address-new' }} @endif">
                    <td width="2%">{!! Form::radio('active_address', $i, $address['active'], array('class' => 'active-address')) !!}</td>
                    <td width="13%">{!! Form::text('Addresses[' . $i . '][first_name]', $address['first_name'], array('class' => 'form-control first-name')) !!}</td>
                    <td width="13%">{!! Form::text('Addresses[' . $i . '][last_name]', $address['last_name'], array('class' => 'form-control last-name')) !!}</td>
                    <td width="18%">{!! Form::text('Addresses[' . $i . '][street]', $address['street'], array('class' => 'form-control street')) !!}</td>
                    <td width="7%">{!! Form::text('Addresses[' . $i . '][house]', $address['house'], array('class' => 'form-control house')) !!}</td>
                    <td width="7%">{!! Form::text('Addresses[' . $i . '][apartment]', $address['apartment'], array('class' => 'form-control apartment')) !!}</td>
                    <td width="12%">{!! Form::text('Addresses[' . $i . '][post_code]', $address['post_code'], array('class' => 'form-control post-code')) !!}</td>
                    <td width="12%">{!! Form::text('Addresses[' . $i . '][city]', $address['city'], array('class' => 'form-control city')) !!}</td>
                    <td width="13%">{!! Form::select('Addresses[' . $i . '][country_id]', $countries, $address['country_id'], array('class' => 'form-control country')) !!}</td>
                    <td width="3%">
                        <button type="button" id="remove-address" class="btn btn-xs btn-danger">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                        {!! Form::hidden('Addresses[' . $i . '][active]', $address['active'], array('class' => 'active-hidden')) !!}
                        {!! Form::hidden('Addresses[' . $i . '][id]', (isset($address['id']) ? $address['id'] : null), array('class' => 'id-hidden')) !!}
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
    {!! Form::close() !!}
</div>
<div class="modal-footer">
    <button type="button" id="save-address" class="btn btn-success" data-dismiss="modal">
        <span class="fa fa-save" role="presentation" aria-hidden="true"></span>
        <span>{{ trans('Сохранить') }}</span>
    </button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="fa fa-ban"></span>
        <span>{{ trans('Admin::admin.cancel') }}</span>
    </button>
</div>

<script id="address-row-template" type="text/x-jsrender">
    <tr class="address-row address-new">
        <td width="2%">{!! Form::radio('active_address', '{^{:active}}', false, array('class' => 'active-address')) !!}</td>
        <td width="13%">{!! Form::text('Addresses[{^{:i}}][first_name]', '{^{:first_name}}', array('class' => 'form-control first-name')) !!}</td>
        <td width="13%">{!! Form::text('Addresses[{^{:i}}][last_name]', '{^{:last_name}}', array('class' => 'form-control last-name')) !!}</td>
        <td width="18%">{!! Form::text('Addresses[{^{:i}}][street]', '{^{:street}}', array('class' => 'form-control street')) !!}</td>
        <td width="7%">{!! Form::text('Addresses[{^{:i}}][house]', '{^{:house}}', array('class' => 'form-control house')) !!}</td>
        <td width="7%">{!! Form::text('Addresses[{^{:i}}][apartment]', '{^{:apartment}}', array('class' => 'form-control apartment')) !!}</td>
        <td width="12%">{!! Form::text('Addresses[{^{:i}}][post_code]', '{^{:post_code}}', array('class' => 'form-control post-code')) !!}</td>
        <td width="12%">{!! Form::text('Addresses[{^{:i}}][city]', '{^{:city}}', array('class' => 'form-control city')) !!}</td>
        <td width="13%">{!! Form::select('Addresses[{^{:i}}][country_id]', $countries, '{^{:country_id}}', array('class' => 'form-control country')) !!}</td>
        <td width="3%">
            <button type="button" id="remove-address" class="btn btn-xs btn-danger">
                <span class="glyphicon glyphicon-minus"></span>
            </button>
            {!! Form::hidden('Addresses[{^{:i}}][active]', '{^{:active}}', array('class' => 'active-hidden')) !!}
            {!! Form::hidden('Addresses[{^{:i}}][id]', '{^{:id}}', array('class' => 'id-hidden')) !!}
        </td>
    </tr>
</script>