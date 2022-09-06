<?php

namespace App\Http\Controllers;

use App\Models\FlightsModel;

class FlightsController extends Controller
{
    public function flights()
    {
        $flights = FlightsModel::all();
        dd($flights);
//        $flights = DB::table('flights')->get();
        return view('flights',compact('flights'));
        dd($flights);
    }
}
