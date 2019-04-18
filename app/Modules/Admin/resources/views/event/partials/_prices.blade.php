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
                        <input type="number" name="prices-[price]" class="form-control" min="0" max="100" disabled>
                    </td>
                    <td class="colorpicker-block">
                        <input type="hidden" name="prices-[color]" class="form-control" value="#ffffff" disabled>
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
                            <input type="number" name="prices[{{ $loop->iteration }}][price]" class="form-control" min="0" max="100" value="{{ $price->price }}">
                        </td>
                        <td class="colorpicker-block">
                            <input type="hidden" name="prices[{{ $loop->iteration }}][color]" class="form-control" value="{{ $price->color }}">
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="prices[{{ $loop->iteration }}][id]" value="{{ $price->id }}">
                            <button class="btn btn-danger btn-xs"
                                    type="button"
                                    data-toggle="modal"
                                    data-target="#deleteItem"
                                    data-url="{{ route('events.deletePrice', ['id' => $price->id]) }}">
                                <i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="3">
                            <p class="text-center">{{ __('Prices are not set') }}</p>
                        </td>
                    </tr>
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
                        <input type="text" name="priceGroups-[name]" class="form-control" disabled>
                    </td>
                    <td>
                        <input type="number" name="priceGroups-[discount]" class="form-control" min="0" max="100" disabled>
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
                            <input type="text" name="priceGroups[{{ $loop->iteration }}][name]" class="form-control" value="{{ $priceGroup->name }}">
                        </td>
                        <td>
                            <input type="number" name="priceGroups[{{ $loop->iteration }}][discount]" class="form-control" min="0" max="100" value="{{ $priceGroup->discount }}">
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
                    <tr class="empty-row">
                        <td colspan="3">
                            <p class="text-center">{{ __('Price groups are not set') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

