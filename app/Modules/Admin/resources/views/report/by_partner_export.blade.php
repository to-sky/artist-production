<table style="background-color: #ccc">
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

<table>
  <thead>
  <tr>
    <th>{{ __('Event') }} / {{ __('Partner') }}</th>
    <th>{{ __('Ticket') }}</th>
    <th>{{ __('Price') }}</th>
    <th>{{ __('Amount') }}</th>
    <th>{{ __('Total') }}</th>
  </tr>
  </thead>

  @foreach($data as $groupName => $groupData)

    <tbody><tr><td colspan="5"></td></tr></tbody>

    <tbody>
    <tr>
      <td colspan="5">{{ $groupName }}</td>
    </tr>

    @foreach($groupData as $reportName => $reportData)

      <tr>
        <td colspan="5">{{ $reportName }}</td>
      </tr>

      @foreach($reportData as $row)

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
        <td>{{ sprintf('%1.2f', $totals[$groupName][$reportName]['discount']) }}</td>
      </tr>

      <tr>
        <td></td>
        <td></td>
        <td>{{ __('Total') }}:</td>
        <td>{{ $totals[$groupName][$reportName]['count'] }}</td>
        <td>{{ sprintf('%1.2f', $totals[$groupName][$reportName]['price']) }}</td>
      </tr>

    @endforeach

    <tr>
      <td></td>
      <td></td>
      <td>{{ __('Total') }}:</td>
      <td>{{ $totals[$groupName]['count'] }}</td>
      <td>{{ sprintf('%1.2f', $totals[$groupName]['price']) }}</td>
    </tr>

    </tbody>

  @endforeach

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