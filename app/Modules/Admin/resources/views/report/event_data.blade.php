@foreach($data as $event)
  <tr>
    <td>{{ $event->id }}</td>
    <td>{{ $event->is_active ? __('Yes') : __('No') }}</td>
    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->date)->format('d.m.Y') }}</td>
    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->date)->format('H:i') }}</td>
    <td>{{ $event->name }}</td>
    <td>{{ $event->city_name }}</td>
    <td>{{ $event->building_name }}</td>
    <td>{{ $event->hall_name }}</td>
    <td>
      <a target="_blank"
         href="{{ route(config('admin.route') . '.reports.export.tickets', ['event' => $event->id]) }}">
        {{ __('Tickets report') }}
      </a>
      <a target="_blank"
         href="{{ route(config('admin.route') . '.reports.export.tickets.unsold', ['event' => $event->id]) }}">
        {{ __('Unsold') }}
      </a>
    </td>
  </tr>
@endforeach