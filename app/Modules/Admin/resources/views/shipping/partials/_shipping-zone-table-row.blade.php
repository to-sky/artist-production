<tr class="hidden shipping-zone-row">
    <td>
        <input name="name" type="text" class="form-control" disabled required>
    </td>
    <td>
        <input name="price" type="number" min="0" class="form-control" disabled required>
    </td>
    <td>
        <select name="countries_id" class="form-control" multiple disabled required>
            @foreach($countries as  $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <button type="button" class="btn btn-danger btn-xs center-block delete-row">
            <i class="fa fa-minus"></i>
        </button>
    </td>
</tr>
