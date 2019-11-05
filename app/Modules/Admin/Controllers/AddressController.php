<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends AdminController
{
    public function manage(Request $request)
    {
        $addresses = $request->get('addresses', array());
        $client_id = $request->get('client_id');
        $ids = [];

        foreach ($addresses as $item) {
            if (array_key_exists('active', $item) && is_null($item['active'])) {
                unset($item['active']);
            }
            if (empty($item['id'])) {
                $address = Address::create($item + ['client_id' => $client_id]);
                $ids[] = $address->id;
            } else {
                $address = Address::find($item['id']);
                if (array_key_exists('id', $item)) {
                    unset($item['id']);
                }
                if ($address) {
                    $address->update($item);
                } else {
                    $address = Address::create($item);
                    $ids[] = $address->id;
                }
            }

        }

        return $ids;

    }

    /**
     * Remove the specified address from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Address::destroy($id);

        return response()->json(null, 204);
    }
}
