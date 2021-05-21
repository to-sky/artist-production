<table>
  <tbody>
    <tr>
      <td>{{ __('Sale period') }}</td>
      <td>
        @if($salePeriodStart && $salePeriodEnd)
          {{ __('from') }} {{ $salePeriodStart }} {{ __('to') }} {{ $salePeriodEnd }}
        @endif
      </td>
    </tr>
    <tr>
      <td>{{ __('Events period') }}</td>
      <td>
        @if($eventPeriodStart && $eventPeriodEnd)
          {{ __('from') }} {{ $eventPeriodStart }} {{ __('to') }} {{ $eventPeriodEnd }}
        @endif
      </td>
    </tr>
  </tbody>
</table>

<br>

<table>
  <thead>
    <tr>
      <th>{{ __('Event') }}</th>
      <th>{{ __('Ticket') }}</th>
      <th>{{ __('Price') }}</th>
      <th>{{ __('Amount') }}</th>
      <th>{{ __('Total') }}</th>
    </tr>
  </thead>

  <tbody><tr><td colspan="5"></td></tr></tbody>

  <tbody>
    <tr>
      <td colspan="5">{{ __('My sales') }}</td>
    </tr>

    @foreach($data as $eventName => $eventData)

      <tr>
        <td colspan="5">{{ $eventName }}</td>
      </tr>

      @foreach($eventData as $row)

        <tr>
          <td></td>
          <td>{{ $displayStatuses[$row->status] }}</td>
          <td>{{ sprintf('%1.2f', $row->price) }}</td>
          <td>{{ $row->count }}</td>
          <td>{{ sprintf('%1.2f', $row->price * $row->count) }}</td>
        </tr>

      @endforeach

      <tr>
        <td></td>
        <td></td>
        <td>{{ __('Discount') }}:</td>
        <td></td>
        <td>{{ sprintf('%1.2f', $totals[$eventName]['discount']) }}</td>
      </tr>

      <tr>
        <td></td>
        <td></td>
        <td>{{ __('Total') }}:</td>
        <td>{{ $totals[$eventName]['count'] }}</td>
        <td>{{ sprintf('%1.2f', $totals[$eventName]['price']) }}</td>
      </tr>

    @endforeach

  </tbody>

  <tbody>
    <tr>
      <td></td>
      <td colspan="2">{{ __('Discount') }}:</td>
      <td></td>
      <td>{{ sprintf('%1.2f', $totals['discount']) }}</td>
    </tr>

    <tr>
      <td></td>
      <td colspan="2">{{ __('Total') }}:</td>
      <td>{{ $totals['count'] }}</td>
      <td>{{ sprintf('%1.2f', $totals['price']) }}</td>
    </tr>
  </tbody>
</table>