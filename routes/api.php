<?php

use Illuminate\Http\Request;

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
Route::middleware(['auth:api'])->group(function () {
    Route::get('/tags/search', 'TagController@search');
    Route::get('/users/search', 'UserController@search');
    Route::get('/users/{user}/subscriptions', 'UserController@subscribe');
    Route::delete('/users/{user}/subscriptions', 'UserController@unsubscribe');
    Route::get('/users/notification', 'UserController@notification');
    Route::get('/user/notification/{id}', 'UserController@notificationMarkAsRead');
    Route::apiResources([
        '/users' => 'UserController',
        '/tags' => 'TagController',
        '/publications' => 'PublicationController'
    ]);
});