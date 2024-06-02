<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function getAll()
    {
        $userId = Auth::id();

        // Obtener los restaurantes creados por el usuario
        $restaurants = Restaurant::with(['reviews' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();

        // Iterar sobre los restaurantes para configurar el campo de reseña
        $restaurants->each(function ($restaurant) {
            if (!$restaurant->reviews->isEmpty()) {
                $restaurant->review = $restaurant->reviews->first();
            } else {
                $restaurant->review = null;
            }
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

    public function getRestaurantMap($star)
    {


        $restaurants = Restaurant::leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
            ->select('restaurants.*', DB::raw('ROUND(AVG(reviews.rating),1) as average_review_rating'))
            ->groupBy('restaurants.id')
            ->get();
        $restaurants = $restaurants->filter(function ($restaurant) use ($star) {
            return $restaurant->average_review_rating >= $star;
        });
        return response()->json($restaurants);
    }
}
