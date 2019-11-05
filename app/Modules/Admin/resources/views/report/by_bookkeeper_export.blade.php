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
  </tbody>
</table>

<table>
  <thead>
  <tr>
    <th>{{ __('Bookkeeper') }}/{{ __('Event') }}</th>
    <th>{{ __('Ticket') }}</th>
    <th>{{ __('Price') }}</th>
    <th>{{ __('Amount') }}</th>
    <th>{{ __('Discount') }}</th>
    <th>{{ __('Total') }}</th>
  </tr>
  </thead>

  @foreach($data as $primaryGrouping => $primaryGroup)

    <tbody>
    <tr><td colspan="6">{{ $primaryGrouping }}</td></tr>
    </tbody>

    <tbody>
    <tr>
      <td></td>
      <td>{{ __('Total') }}</td>
      <td></td>
      <td>{{ $totals[$primaryGrouping]['count'] }}</td>
      <td>{{ sprintf('%1.2f', $totals[$primaryGrouping]['discount']) }}</td>
      <td>{{ sprintf('%1.2f', $totals[$primaryGrouping]['price']) }}</td>
    </tr>
    </tbody>

    @foreach($primaryGroup as $secondaryGrouping => $secondaryGroup)

      <tbody>
      <tr><td colspan="6">{{ $secondaryGrouping }}</td></tr>
      </tbody>

      <tbody>
      @foreach($secondaryGroup as $row)

        <tr>
          <td></td>
          <td>{{ $displayStatuses[$row->status] }}</td>
          <td>{{ sprintf('%1.2f', $row->price) }}</td>
          <td>{{ $row->count }}</td>
          <td>{{ sprintf('%1.2f', $row->discount) }}</td>
          <td>{{ sprintf('%1.2f', $row->price * $row->count) }}</td>
        </tr>

      @endforeach
      </tbody>

      <tbody>
      <tr>
        <td></td>
        <td>{{ __('Total') }}</td>
        <td></td>
        <td>{{ $totals[$primaryGrouping][$secondaryGrouping]['count'] }}</td>
        <td>{{ sprintf('%1.2f', $totals[$primaryGrouping][$secondaryGrouping]['discount']) }}</td>
        <td>{{ sprintf('%1.2f', $totals[$primaryGrouping][$secondaryGrouping]['price']) }}</td>
      </tr>
      </tbody>

    @endforeach

  @endforeach

  <tbody class="subsep"><tr><td colspan="6"></td></tr></tbody>

  <tbody>
  <tr>
    <td></td>
    <td>{{ __('Total') }}</td>
    <td></td>
    <td>{{ $totals['count'] }}</td>
    <td>{{ sprintf('%1.2f', $totals['discount']) }}</td>
    <td>{{ sprintf('%1.2f', $totals['price']) }}</td>
  </tr>
  </tbody>
</table>