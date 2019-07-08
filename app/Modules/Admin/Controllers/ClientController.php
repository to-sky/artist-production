<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Address;
use App\Models\Country;
use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Admin\Services\RedirectService;
use App\Services\ClientService;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Schema;
use App\Models\Profile;
use App\Modules\Admin\Requests\CreateClientRequest;
use App\Modules\Admin\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
use App\Exports\ClientsExport;
use Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


use Prologue\Alerts\Facades\Alert;

/**
 * TODO: revamp whole controller to use user with role client instead of Client model
 *
 * Class ClientController
 * @package App\Modules\Admin\Controllers
 */
class ClientController extends AdminController {

    public function __construct(ClientService $clientService, RedirectService $redirectService)
    {
        $this->authorizeResource($clientService->getModelClass(), 'user');

        parent::__construct($redirectService);
    }

	/**
	 * Display a listing of clients
	 *
     * @param ClientService $clientService
     * @param Request $request
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function index(ClientService $clientService, Request $request)
    {
        $this->authorize('index', Profile::class);

        $clients = $clientService->query();

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
        $types = Profile::getTypes();
	    
	    return view('Admin::client.create', compact('countries', 'countryCodes', 'addresses', 'types'));
	}

    /**
     * Store a newly created client in storage.
     *
     * @param ClientService $clientService
     * @param CreateClientRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(ClientService $clientService, CreateClientRequest $request)
	{
		$clientService->create(
		    $clientService->getDataFromCreateRequest($request)
        );

        Alert::success(trans('Admin::admin.controller-successfully_created', ['item' => trans('Admin::models.Client')]))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified client.
	 *
     * @param ClientService $clientService
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit(ClientService $clientService, $id)
	{
	    $client = $clientService->get($id);
        $countries = Country::pluck('name', 'id')->toArray();
        $addresses = $client->addresses->toArray();
        $countryCodes = Country::pluck('code')->toArray();
        $types = Profile::getTypes();
	    
		return view('Admin::client.edit', compact('client', 'countries', 'countryCodes', 'addresses', 'types'));
	}

    /**
     * Update the specified client in storage.
     *
     * @param Profile $profile
     * @param UpdateClientRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function update(Profile $profile, UpdateClientRequest $request)
	{
        $profile->update($request->all());

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
		Profile::destroy($id);

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
            Profile::destroy($toDelete);
        } else {
            Profile::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.clients.index');
    }

    public function excel()
    {
        return Excel::download(new ClientsExport(), 'clients.xlsx');
    }

}
