<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function get(){

        $restaurants = Restaurant::all();

        return response()->json($restaurants);
    }

    public function add(Request $request){

        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant -> longitude = $request->longitude;
        $restaurant -> latitude = $request->latitude;
        $restaurant -> user_id = Auth::id();
        $restaurant->save();

        return response()->json('ok');
    }   
}
