<?php

namespace App\Http\Controllers;

use App\Helpers\CountryHelper;
use App\Http\Requests\AddressRequest;
use App\Models\Address;

use App\Models\Country;
use Auth;

class AddressController extends Controller
{
    /**
     * Index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses;

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Create page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $countries = CountryHelper::getList();

        return view('addresses.create', compact('countries'));
    }

    /**
     * Save action
     *
     * @param AddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(AddressRequest $request)
    {
        $data = array_merge($request->get('address'), [
            'user_id' => Auth::id(),
        ]);

        $address = Address::create($data);

        return redirect()->route('address.index');
    }

    /**
     * Show/Edit page
     *
     * @param Address $address
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Address $address)
    {
        $countries = CountryHelper::getList();

        return view('addresses.show', compact('address', 'countries'));
    }

    /**
     * Update action
     *
     * @param AddressRequest $request
     * @param Address $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AddressRequest $request, Address $address)
    {
        $address->fill($request->get('address'));
        $address->save();

        return redirect()->route('address.index');
    }

    /**
     * Remove action
     *
     * @param Address $address
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function remove(Address $address)
    {
        if (Address::whereUserId(Auth::id())->count() <= 1)
            return redirect()->back()->withErrors(__('Can\'t delete last address'));

        $address->delete();

        return redirect()->back();
    }
}
