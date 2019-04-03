<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Country;
use App\Models\Shipping;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use App\Modules\Admin\Requests\ShippingRequest;
use Prologue\Alerts\Facades\Alert;

class ShippingController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin::shipping.index', [
            'shippings' => Shipping::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->generateParams();

        return view('Admin::shipping.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ShippingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ShippingRequest $request)
    {
        $shipping = Shipping::create($request->except('shipping_zones'));

        $this->storeShippingZones($shipping);

        Alert::success(trans('Admin::admin.controller-successfully_created', ['item' => trans('Admin::models.Shipping')]))->flash();

        $this->redirectService->setRedirect($request);

        return $this->redirectService->redirect($request);
    }

    /**
     * Store shipping zones
     *
     * @param Shipping $shipping
     * @return null
     */
    public function storeShippingZones(Shipping $shipping)
    {
        $shippingZones = collect(request()->shipping_zones);

        if ($shippingZones->isEmpty()) {
            return null;
        }

        return $shippingZones->map(function ($item) use ($shipping) {
            $shippingZone = $shipping->shipping_zones()->create($item);

            if (isset($item['countries_id'])) {
                $shippingZone->countries()->sync(array_filter($item['countries_id']));
            }

            return $shippingZone;
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Shipping $shipping
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipping $shipping)
    {
        $this->generateParams();

        return view('Admin::shipping.edit', compact('shipping'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ShippingRequest $request
     * @param Shipping $shipping
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ShippingRequest $request, Shipping $shipping)
    {
        $shipping->update($request->all());

        $shipping->shipping_zones()->delete();

        $this->storeShippingZones($shipping);

        Alert::success(trans('Admin::admin.controller-successfully_updated', ['item' => trans('Admin::models.Shipping')]))->flash();

        $this->redirectService->setRedirect($request);

        return $this->redirectService->redirect($request);
    }

    /**
     * Set default shipping
     *
     * @param Shipping $shipping
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDefaultShipping(Shipping $shipping)
    {
        $shipping->setDefault();

        return redirect()->back();
    }

    /**
     * Delete shipping zones
     *
     * @param ShippingZone $shippingZone
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteShippingZone(ShippingZone $shippingZone)
    {
        $shippingZone->delete();

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Shipping $shipping
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Shipping $shipping)
    {
        $shipping->delete();

        return response()->json(null, 204);
    }

    /**
     * Mass delete function from index page
     * @param Request $request
     *
     * @return mixed
     */
    public function massDelete(Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            Shipping::destroy($toDelete);
        } else {
            Shipping::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.shippings.index');
    }

    /**
     * Share the same variables for different views
     */
    public function generateParams()
    {
        $shippingZones = ShippingZone::get()->pluck('name', 'id');
        $countries = Country::all()->pluck('name', 'id')->prepend('All World', Country::WORLD);

        view()->share('shippingZones', $shippingZones);
        view()->share('countries', $countries);
    }
}
