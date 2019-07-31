<section class="by-bookkeeper-report">
  <h3>{{ __('Bookkeepers sales') }}</h3>

  <table class="bookkeeper-report-table">
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

    <tbody class="totals">
      <tr>
        <td></td>
        <td>{{ __('Total') }}</td>
        <td></td>
        <td>{{ $totals['count'] }}</td>
        <td>{{ sprintf('%1.2f', $totals['discount']) }}</td>
        <td>{{ sprintf('%1.2f', $totals['price']) }}</td>
      </tr>
    </tbody>

    @foreach($data as $primaryGrouping => $primaryGroup)

      <tbody class="sep"><tr><td colspan="6"></td></tr></tbody>

      <tbody class="primary-group-name">
        <tr><td colspan="6">{{ $primaryGrouping }}</td></tr>
      </tbody>

      <tbody class="subsep"><tr><td colspan="6"></td></tr></tbody>

      <tbody class="primary-group-total">
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

        <tbody class="subsep"><tr><td colspan="6"></td></tr></tbody>

        <tbody class="secondary-group-name">
          <tr><td colspan="6">{{ $secondaryGrouping }}</td></tr>
        </tbody>

        <tbody class="secondary-group">
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

        <tbody class="secondary-group-total">
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
  </table>
</section>