<?php

namespace App\Modules\Admin\Controllers;

use App\Helpers\CountryHelper;
use App\Libs\Kartina\Traits\ClientTrait;
use App\Models\Client;
use App\Models\Country;
use App\Models\User;
use App\Modules\Admin\Services\RedirectService;
use App\Services\ClientService;
use App\Models\Profile;
use App\Modules\Admin\Requests\CreateClientRequest;
use App\Modules\Admin\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
use App\Exports\ClientsExport;
use Excel;


use Prologue\Alerts\Facades\Alert;

/**
 * Class ClientController
 * @package App\Modules\Admin\Controllers
 */
class ClientController extends AdminController {

    use ClientTrait;

    public function __construct(ClientService $clientService, RedirectService $redirectService)
    {
        $this->authorizeResource($clientService->getModelClass(), 'client');

        parent::__construct($redirectService);

        $this->initClientTrait();
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
        $this->authorize('index', Client::class);

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
	    $countries = CountryHelper::getList();
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

        Alert::success(trans(
            'Admin::admin.controller-successfully_created',
            ['item' => trans('Admin::models.Client')]
        ))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified client.
	 *
	 * @param Client $client
     * @return \Illuminate\View\View
	 */
	public function edit(Client $client)
	{
	    $client->loadMissing('profile', 'addresses');

        $countries = CountryHelper::getList();
        $addresses = $client->addresses->toArray();
        $countryCodes = Country::pluck('code')->toArray();
        $types = Profile::getTypes();
        $clientAddress = $client->addresses()->active()->first();

		return view('Admin::client.edit', compact('client', 'countries', 'countryCodes', 'addresses', 'types', 'clientAddress'));
	}

    /**
     * Update the specified client in storage.
     *
     * @param ClientService $clientService
     * @param Client $client
     * @param UpdateClientRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function update(ClientService $clientService, Client $client, UpdateClientRequest $request)
	{
	    $previousMail = $client->email;

	    $clientService->update(
	        $client,
            $clientService->getDataFromUpdateRequest($request)
        );

        if (($previousMail !== $client->email) && $client->kartina_id) {
            $this->syncKartinaClientForUser($client);
        }

        Alert::success(trans('Admin::admin.controller-successfully_updated', ['item' => trans('Admin::models.Client')]))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

    /**
     * Remove the specified client from storage.
     *
     * @param ClientService $clientService
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
	public function destroy(ClientService $clientService, Client $client)
	{
	    $clientService->delete($client);

		return response()->json(null, 204);
	}

    /**
     * Mass delete function from index page
     *
     * @param ClientService $clientService
     * @param Request $request
     * @return mixed
     */
    public function massDelete(ClientService $clientService, Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            $clientService->delete($toDelete);
        } else {
            Client::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.clients.index');
    }

    /**
     * Export data in excel format
     *
     * @return mixed
     */
    public function excel()
    {
        return Excel::download(new ClientsExport(), 'clients.xlsx');
    }
}
