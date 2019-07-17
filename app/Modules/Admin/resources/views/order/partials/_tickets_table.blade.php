<div>
    <table id="ticketsTable" class="table table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <td>{{ __('Ticket') }}</td>
                <td>{{ __('Price') }}</td>
                <td>{{ __('Discount') }}</td>
                <td>{{ __('Final price') }}</td>
                <td width="12">{{ __('Admin::admin.delete') }}</td>
            </tr>
        </thead>

        @php
            $ticketsFinalPrice = 0;
        @endphp

        <tbody>
        @forelse($ticketsData as $eventName => $tickets)
            <tr data-event="title" data-event-id="{{ $tickets[0]->event->id }}">
                <td colspan="5" class="text-primary">
                    <b>{{ $eventName }},</b> {{ $tickets[0]->event->date->format('d.m.Y, H:i') }}
                </td>
            </tr>
            @foreach($tickets as $ticket)
                @php
                    $ticketsFinalPrice += $ticket->getBuyablePrice();
                @endphp
                <tr data-event-id="{{ $ticket->event->id }}" data-ticket-id="{{ $ticket->id }}">
                    <td>Ряд: {{ $ticket->place->row }} Место: {{ $ticket->place->num }}</td>
                    <td data-price="{{ $ticket->getBuyablePrice() }}">{{ $ticket->getBuyablePrice() }} &euro;</td>
                    <td>
                        @role(\App\Models\Role::PARTNER)
                            0.00
                        @else
                            <a href="#" class="set-discount" data-toggle="modal" data-target="#discountModal">0.00</a>
                        @endrole
                    </td>
                    <td data-price-final="{{ $ticket->getBuyablePrice() }}">{{ $ticket->getBuyablePrice() }} &euro;</td>
                    <td class="text-center">
                        <a href="#" class="delete-ticket" data-ticket-id="{{ $ticket->id }}">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="7" class="text-center">
                    <small>{{ __(':items not selected', ['items' => __('Tickets')]) }}</small>
                </td>
            </tr>
        @endforelse
        </tbody>

        @if($ticketsFinalPrice)
        <tfoot class="text-bold text-sm">
            <tr>
                <td colspan="4" class="text-right">{{ __('Tickets price') }}:</td>
                <td><span id="allTicketsPrice">{{ $ticketsFinalPrice }}</span> &euro;</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">{{ __('Total discount') }}:</td>
                <td>
                    @role(\App\Models\Role::PARTNER)
                        0.00
                    @else
                        <a href="#" id="mainDiscount"
                           data-toggle="modal"
                           data-target="#discountModal"
                           data-discount="all">0.00</a>
                    @endrole

                    <span id="discountTypeValue" data-type="percent"></span>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">{{ __('The total cost') }}:</td>
                <td><span id="allTicketsFinalPrice">{{ $ticketsFinalPrice }}</span> &euro;</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
