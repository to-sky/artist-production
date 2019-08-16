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
                        <th width="40%">{{ __('Name') }}</th>
                        <th>{{ __('Commission') }}</th>
                        <th>Email</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Type') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <input type="radio" name="client" value="{{ $user->id }}">
                            </td>
                            <td>{{ $user->id }}</td>
                            <td data-type="name" style="word-break: break-word">{{ $user->full_name }}</td>
                            <td data-commission="{{ $user->profile->commission }}">{{ $user->profile->commission }} %</td>
                            <td data-type="email">{{ $user->email }}</td>
                            <td  data-type="phone">{{ $user->profile->phone }}</td>
                            <td>{{ $user->profile->typeLabel }}</td>
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
