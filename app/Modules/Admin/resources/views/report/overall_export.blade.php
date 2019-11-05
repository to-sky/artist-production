<table>
  <thead>
  <tr>
    <th>{{ __('Event') }}</th>
    <th>{{ __('Sold') }}, {{ __('pc.') }}</th>
    <th>{{ __('Sum') }}</th>
    <th>{{ __('Realization') }}, {{ __('pc.') }}</th>
    <th>{{ __('Sum') }}</th>
    <th>{{ __('Total') }}, {{ __('pc.') }}</th>
    <th>{{ __('Total') }}</th>
  </tr>
  </thead>

  <tbody>
  @foreach($data as $row)
    <tr>
      <td>{{ $row['event_name'] }}</td>
      <td>{{ $row['sold_count'] }}</td>
      <td>{{ sprintf('%1.2f', $row['sold_price']) }}</td>
      <td>{{ $row['realization_count'] }}</td>
      <td>{{ sprintf('%1.2f', $row['realization_price']) }}</td>
      <td class="totals">{{ $row['sold_count'] + $row['realization_count'] }}</td>
      <td class="totals">{{ sprintf('%1.2f', $row['sold_price'] + $row['realization_price']) }}</td>
    </tr>
  @endforeach

  <tr>
    <td>{{ __('Total') }}</td>
    <td>{{ $data->sum('sold_count') }}</td>
    <td>{{ sprintf('%1.2f', $data->sum('sold_price')) }}</td>
    <td>{{ $data->sum('realization_count') }}</td>
    <td>{{ sprintf('%1.2f', $data->sum('realization_price')) }}</td>
    <td>{{ $data->sum(function ($r) { return $r['realization_count'] + $r['sold_count']; }) }}</td>
    <td>{{ sprintf('%1.2f', $data->sum(function ($r) { return $r['sold_price'] + $r['realization_price']; })) }}</td>
  </tr>
  </tbody>
</table>