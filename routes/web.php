<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

//Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Update our 'home' route to redirect to /cards
Route::get('/home', function () {
    return redirect()->route('cards.index');
})->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('cards', [CardController::class, 'index'])->name('cards.index');
    Route::post('cards', [CardController::class, 'store'])->name('cards.store');
    Route::put('cards/sync', [CardController::class, 'sync'])->name('cards.sync');
    Route::put('cards/{card}', [CardController::class, 'update'])->name('cards.update');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('statuses', [StatusController::class, 'store'])->name('statuses.store');
    Route::put('statuses', [StatusController::class, 'update'])->name('statuses.update');
});

