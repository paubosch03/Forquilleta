<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/google-callback', function () {
    $user = Socialite::driver('google')->user();

    $userExist = User::where('email', $user->email)->first();
    if ($userExist) {
        $user = $userExist;
    } else {
        $user = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'oauth_id' => $user->id
        ]);
    }
    Auth::login($user);
    return redirect('/');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/restaurantes', function () {
    return Inertia::render('Restaurantes/Restaurantes');
})->name('restaurantes');

Route::get('/restauranteReview',function(){
    return Inertia::render('Restaurantes/RestaurantesReview');
})->name('restauranteReview');


Route::get('/restauranteMap',function(){
    return Inertia::render('Restaurantes/RestaurantMap');
})->name('restauranteMap');

Route::get('/restaurantes/getAll', [RestaurantController::class, 'getAll'])-> middleware(['auth', 'verified']);
Route::get('/restaurantes/getMyRestaurants', [RestaurantController::class, 'getMyRestaurants'])-> middleware(['auth', 'verified']);
Route::post('/restaurants/add', [RestaurantController::class, 'add'])-> middleware(['auth', 'verified']);
Route::delete('/restaurants/delete/{id}', [RestaurantController::class, 'delete'])-> middleware(['auth', 'verified']);
Route::get('/restaurants/update/{id}', [RestaurantController::class, 'update'])-> middleware(['auth', 'verified']);
Route::get('/restaurants/getRestaurantMap/{star}', [RestaurantController::class, 'getRestaurantMap'])-> middleware(['auth', 'verified']);

Route::post('/review',[ReviewController::class, 'add'])-> middleware(['auth', 'verified']);
Route::get('/review/update/{id}', [ReviewController::class, 'update'])-> middleware(['auth', 'verified']);
Route::delete('/review/delete/{id}', [ReviewController::class, 'delete'])-> middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
