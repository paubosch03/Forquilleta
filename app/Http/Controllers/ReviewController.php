<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function add (Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'string|max:500',
        ]);

        $review = new Review();
        $review->user_id = Auth::id();
        $review->restaurant_id = $request->restaurant_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return response()->json($review, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'string|max:500',
        ]);

        $review = Review::find($id);
        if ($review) {
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->save();
            return response()->json($review);
        } else {
            return response()->json('Review not found', 404);
        }
    }

    public function delete($id)
    {
        $review = Review::find($id);
        if ($review) {
            $review->delete();
            return response()->json('Review deleted successfully');
        } else {
            return response()->json('Review not found', 404);
        }
    }
}
