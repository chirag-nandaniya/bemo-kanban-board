<?php

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware('auth:api')->get('/list-cards', function(Request $request) {
Route::get('/list-cards', function(Request $request) {

    $query = Card::query();

    if($request->date != ""){
        $query->where('created_at', $request->date);
    }

    if($request->status){
        $query->where('status_id', $request->status);
    }

    return response()->json($query->get());
});