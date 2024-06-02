<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function getAll()
    {
        // Obtener el ID del usuario autenticado
        $userId = Auth::id();
        // // Realizar la consulta con join
        $restaurants = Restaurant::with(['reviews' => function ($query) use ($userId) {
            $query->where('user_id', $userId)->limit(1);
        }])->get();
        $restaurants->each(function ($restaurant) {
            $restaurant->review = $restaurant->reviews->first();
            unset($restaurant->reviews);
        });
        return response()->json($restaurants);
    }

    public function getMyRestaurants()
    {

        $restaurants = Restaurant::where('user_id', Auth::id())->get();

        return response()->json($restaurants);
    }

    public function add(Request $request)
    {

        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->city = $request->city;
        $restaurant->longitude = $request->longitude;
        $restaurant->latitude = $request->latitude;
        $restaurant->user_id = Auth::id();
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
