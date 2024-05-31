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
        $restaurant->city = $request->city;
        $restaurant -> longitude = $request->longitude;
        $restaurant -> latitude = $request->latitude;
        $restaurant -> user_id = Auth::id();
        $restaurant->save();

        return response()->json('ok');
    }   

    public function delete($id)
    {
        $restaurant = Restaurant::find($id);
        if ($restaurant) {
            $restaurant->delete();
            return response()->json('Restaurant deleted successfully');
        } else {
            return response()->json('Restaurant not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);
        if ($restaurant) {
            $restaurant->name = $request->name;
            $restaurant->description = $request->description;
            $restaurant->city = $request->city;
            $restaurant->save();
            return response()->json('Restaurant updated successfully');
        } else {
            return response()->json('Restaurant not found', 404);
        }
    }
}
