<?php

namespace App\Services;


use App\Models\Country;

use App\Models\Shipping;

class ShippingService
{
    /**
     * Returns array of cheapest shipping zones for country
     *
     * @param Country $country
     * @return array
     */
    public function getShippingOptionsForCountry(Country $country)
    {
        $shippings = Shipping
            ::with([
                'shippingZones' => function($q) use($country) {
                    $q
                        ->whereHas('countries', function($q) use($country) {
                            $q->where('id', $country->id);
                        })
                        ->orWhereDoesntHave('countries')
                        ->orderBy('price')
                    ;
                },
            ])
            ->whereHas('shippingZones', function($q) use($country) {
                $q->whereHas('countries', function($q) use($country) {
                    $q->where('id', $country->id);
                })
                ->orWhereDoesntHave('countries');
            })
            ->get()
        ;

        $data = [];
        foreach ($shippings as $shipping) {
            $cheapestZone = $shipping->shippingZones->first();

            $data[] = [
                'name' => $shipping->name,
                'default' => $shipping->default,
                'id' => $cheapestZone->id,
                'price' => $cheapestZone->price,
            ];
        }

        usort($data, function($a, $b) {
            return $a['price'] < $b['price'] ? -1 : 1;
        });

        return $data;
    }
}
