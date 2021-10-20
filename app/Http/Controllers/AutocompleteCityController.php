<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutocompleteCityController extends Controller
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
            'USA' => 'New York City',
            'USA' => 'New Jersey',
            'USA' => 'Chicago',
            'USA' => 'Dallas',

            'Canada' => 'Otowa',
            'Canada' => 'Alberta',

            'UK' => 'London',
            'UK' => 'Bradford',
            'UK' => 'Hereford'
        ];

        return response()->json(array_values($data));
    }
}
