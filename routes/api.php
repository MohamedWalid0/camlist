<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\PetController;
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

//get bids
Route::get('/{pet}/bids' , [BidController::class , 'listBids'] );

//add bid
Route::post('/{pet}/bids' , [BidController::class , 'addBid'] );

// list winners
Route::get('/{pet}/winners' , [BidController::class , 'listWinners'] );
