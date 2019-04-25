<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Prices') }}</h3>
    </div>

    <div class="box-body">
        <table id="pricesTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Price') }}</th>
                    <th class="col-md-1 text-center">{{ __('Color') }}</th>
                    <th class="col-md-1 text-center">
                        <button type="button" class="btn btn-success btn-xs" id="addPrice">
                            <i class="fa fa-plus"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="hidden prices-row">
                    <td>
                        <input type="number" name="prices-[price]" class="form-control"
                               min="0" max="100" disabled required>
                    </td>
                    <td class="colorpicker-block">
                        <input type="hidden" name="prices-[color]" class="form-control"
                               value="#ffffff" disabled>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-xs delete-row">
                            <i class="fa fa-minus"></i>
                        </button>
                    </td>
                </tr>

                @forelse($event->prices as $price)
                    <tr class="prices-row">
                        <td>
                            <input type="number" name="prices[{{ $loop->iteration }}][price]"
                                   class="form-control" min="0" max="100" value="{{ $price->price }}" required>
                        </td>
                        <td class="colorpicker-block">
                            <input type="hidden" name="prices[{{ $loop->iteration }}][color]"
                                   class="form-control" value="{{ $price->color }}">
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="prices[{{ $loop->iteration }}][id]" value="{{ $price->id }}">
                            @if($price->ticket->isEmpty())
                                <button class="btn btn-danger btn-xs"
                                        type="button"
                                        data-toggle="modal"
                                        data-target="#deleteItem"
                                        data-url="{{ route('events.deletePrice', ['id' => $price->id]) }}">
                                    <i class="fa fa-minus"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-xs btn-danger disabled" data-toggle="tooltip" title="{{ __('Cannot be delete. Only edit') }}"><i class="fa fa-minus"></i></button>
                            @endif
                        </td>
                    </tr>
                @empty
                    @include('Admin::partials.empty-table-row', ['itemName' => 'Prices'])
                @endforelse
            </tbody>
        </table>

        <br>
        <h4>{{ __('Price groups') }}</h4>
        <table id="priceGroupTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th class="col-md-2">{{ __('Discount') }}, %</th>
                    <th class="col-md-1 text-center">
                        <button type="button" id="addPriceGroup" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="hidden price-groups-row">
                    <td>
                        <input type="text" name="priceGroups-[name]" class="form-control" disabled required>
                    </td>
                    <td>
                        <input type="number" name="priceGroups-[discount]"
                               class="form-control" min="0" max="100" disabled required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-xs delete-row">
                            <i class="fa fa-minus"></i>
                        </button>
                    </td>
                </tr>

                @forelse($event->priceGroups as $priceGroup)
                    <tr class="price-groups-row">
                        <td>
                            <input type="text" name="priceGroups[{{ $loop->iteration }}][name]"
                                   class="form-control"
                                   value="{{ $priceGroup->name }}"
                                   required>
                        </td>
                        <td>
                            <input type="number" name="priceGroups[{{ $loop->iteration }}][discount]"
                                   class="form-control"
                                   min="0" max="100"
                                   value="{{ $priceGroup->discount }}"
                                   required>
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="priceGroups[{{ $loop->iteration }}][id]" value="{{ $priceGroup->id }}">
                            <button class="btn btn-danger btn-xs"
                                    type="button"
                                    data-toggle="modal"
                                    data-target="#deleteItem"
                                    data-url="{{ route('events.deletePriceGroup', ['id' => $priceGroup->id]) }}">
                                <i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    @include('Admin::partials.empty-table-row', ['itemName' => 'Price groups'])
                @endforelse
            </tbody>
        </table>
    </div>
    <br>
</div>

