<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutocompleteCountryController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = [
            'New York City' => 'USA',
            'New Jersey' => 'USA',
            'Chicago' => 'USA',
            'Dallas' => 'USA',

            'Otowa' => 'Canada',
            'Alberta' => 'Canada',

            'London' => 'UK',
            'Bradford' => 'UK',
            'Hereford' => 'UK'
        ];

        $dataCollection = collect($data);

        return response()->json(array_values($data));
    }
}
