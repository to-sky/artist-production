<!-- Clients modal -->
<div class="modal fade" id="clientsModal" tabindex="-1" role="dialog" aria-labelledby="clientsModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="clientsModalLabel">{{ __('Admin::admin.Clients') }}</h4>
            </div>
            <div class="modal-body">
                <table id="datatable" class="table table-bordered table-hover table-selected">
                    <thead>
                    <tr>
                        <th></th>
                        <th>id</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Commission') }}</th>
                        <th>Email</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Type') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>
                                <input type="radio" name="client" value="{{ $client->id }}">
                            </td>
                            <td>{{ $client->id }}</td>
                            <td data-type="name">{{ $client->full_name }}</td>
                            <td>{{ $client->commission }}%</td>
                            <td data-type="email">{{ $client->email }}</td>
                            <td  data-type="phone">{{ $client->phone }}</td>
                            <td>{{ $client->getTypeLabel($client->type) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" id="getClient" class="btn btn-primary">{{ __('Select') }}</button>
            </div>
        </div>
    </div>
</div>
