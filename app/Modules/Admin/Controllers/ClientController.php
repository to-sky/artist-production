<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Address;
use App\Models\Country;
use App\Modules\Admin\Controllers\AdminController;
use Redirect;
use Schema;
use App\Models\Client;
use App\Modules\Admin\Requests\CreateClientRequest;
use App\Modules\Admin\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
use App\Exports\ClientsExport;
use Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


use Prologue\Alerts\Facades\Alert;

class ClientController extends AdminController {

	/**
	 * Display a listing of clients
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $clients = Client::all();

		return view('Admin::client.index', compact('clients'));
	}

	/**
	 * Show the form for creating a new client
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $countries = Country::pluck('name', 'id')->toArray();
        $countryCodes = Country::pluck('code')->toArray();
	    $addresses = [];
	    
	    return view('Admin::client.create', compact('countries', 'countryCodes', 'addresses'));
	}

    /**
     * Store a newly created client in storage.
     *
     * @param CreateClientRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(CreateClientRequest $request)
	{
		$client = Client::create($request->all());

		$addresses = $request->get('Addresses');

		if (empty($addresses)) {
            Address::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'street' => $request->get('street'),
                'house' => $request->get('house'),
                'apartment' => $request->get('apartment'),
                'city' => $request->get('city'),
                'country_id' => $request->get('country_id'),
                'active' => Address::ACTIVE,
                'client_id' => $client->id
            ]);
        } else {
		    foreach ($addresses as $address) {
		        Address::create($address + ['client_id' => $client->id]);
            }
        }

        Alert::success(trans('Admin::admin.controller-successfully_created', ['item' => trans('Admin::models.Client')]))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified client.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$client = Client::find($id);
        $countries = Country::pluck('name', 'id')->toArray();
        $addresses = $client->addresses->toArray();
        $countryCodes = Country::pluck('code')->toArray();
	    
		return view('Admin::client.edit', compact('client', 'countries', 'countryCodes', 'addresses'));
	}

    /**
     * Update the specified client in storage.
     *
     * @param $id
     * @param UpdateClientRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function update($id, UpdateClientRequest $request)
	{
		$client = Client::findOrFail($id);

		$client->update($request->all());

        Alert::success(trans('Admin::admin.controller-successfully_updated', ['item' => trans('Admin::models.Client')]))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

    /**
     * Remove the specified client from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
	public function destroy($id)
	{
		Client::destroy($id);

		return response()->json(null, 204);
	}

    /**
     * Mass delete function from index page
     *
     * @param Request $request
     * @return mixed
     */
    public function massDelete(Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            Client::destroy($toDelete);
        } else {
            Client::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.clients.index');
    }

    public function excel()
    {
        return Excel::download(new ClientsExport(), 'clients.xlsx');
    }

}
