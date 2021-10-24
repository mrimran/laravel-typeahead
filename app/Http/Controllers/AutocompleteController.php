<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    protected $countryCity = [
        ['country' => 'USA', 'city' => 'New York City'],
        ['country' => 'USA', 'city' => 'New Jersey'],
        ['country' => 'USA', 'city' => 'Chicago'],
        ['country' => 'USA', 'city' => 'Dallas'],

        ['country' => 'Canada', 'city' => 'Otowa'],
        ['country' => 'Canada', 'city' => 'Alberta'],

        ['country' => 'UK', 'city' => 'London'],
        ['country' => 'UK', 'city' => 'Bradford'],
        ['country' => 'UK', 'city' => 'Hereford'],
    ];

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $dataCollection = $filteredData = collect($this->countryCity);

        if ($request['selected_country'] == '0') {
            unset($request['selected_country']);
        }

        if ($request['selected_city'] == '0') {
            unset($request['selected_city']);
        }

        if ($request['selected_country'] && $request['auto_trigger'] && $request['type'] == 'city') {
            $filteredData = $dataCollection->filter(function ($value, $key) use ($request) {
                return $value['country'] == $request['selected_country'] ? $value : null;
            })->pluck('city');
        } elseif ($request['selected_city'] && $request['auto_trigger'] && $request['type'] == 'country') {
            $filteredData = $dataCollection->filter(function ($value, $key) use ($request) {
                return $value['city'] == $request['selected_city'] ? $value : null;
            })->pluck('country');
        } elseif ($request['type'] == 'city') {
            $filteredData = $dataCollection->pluck('city');
        } else {
            $filteredData = $dataCollection->pluck('country');
        }

        if ($request['query'] && $request['query'] != 'all' && !$request['auto_trigger']) {
            $filteredData = $filteredData->filter(function ($value, $key) use ($request) {
                return stripos($value, $request['query']) !== false ? $value : null;
            });
        }

        return response()->json($filteredData->values()->unique()->values());
    }
}
