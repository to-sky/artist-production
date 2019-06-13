<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;

use App\Models\Country;
use Auth;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses;

        return view('addresses.index', compact('addresses'));
    }

    public function show(Address $address)
    {
        $countries = Country::pluck('name', 'id');

        return view('addresses.show', compact('address', 'countries'));
    }

    public function update(AddressRequest $request, Address $address)
    {
        $address->fill($request->get('address'));
        $address->save();

        return redirect()->route('address.index');
    }

    public function remove(Address $address)
    {
        $address->delete();

        return redirect()->back();
    }
}
