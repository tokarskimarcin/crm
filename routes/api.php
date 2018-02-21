<?php

use Illuminate\Http\Request;
//załadowanie modelu
use App\User;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * Ścieżka do danych http://localhost/api/userApi/cokolwiek/cokolwiek
 */
Route::get('userApi/{id}', function($id) {
    return User::find($id);
});
Route::get('getMedicalScan/{name}', function($name){
    //return 1;
    $path = storage_path('app/medicalscan/' . $name);
    return response()->download($path);
});