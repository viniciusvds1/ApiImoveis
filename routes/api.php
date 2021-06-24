<?php

use App\Http\Controllers\Api\RealStatePhotoController;
use App\RealStatePhoto;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->group(function(){

   Route::post('login', 'Auth\\LoginJwtController@login')->name('login');
   Route::get('logout', 'Auth\\LoginJwtController@logout')->name('logout');
   Route::get('refresh', 'Auth\\LoginJwtController@refresh')->name('refresh');

   Route::get('/search', 'RealStateSearchController@index')->name('search');
   Route::get('/search/{real_state_id}', 'RealStateSearchController@show')->name('search_single');

   Route::name('users.')->group(function(){

    Route::resource('users', 'UserController');

   });

   Route::middleware(['jwt.auth'])->group(function () {

    Route::name('real_states.')->group(function(){
        Route::resource('real-states', 'RealStateController');

    });


    Route::name('categories.')->group(function(){
        Route::get('categories/{id}/real-states', 'CategoryController@realState');
        Route::resource('categories', 'CategoryController');

    });

    Route::name('photos.')->prefix('photos')->group(function(){
        route::delete('/{id}', 'RealStatePhotoController@remove')->name('delete');
        route::put('/set-thumb/{photoId}/{realStateId}', 'RealStatePhotoController@setThumb')->name('delete');

    });
   });
}



);
