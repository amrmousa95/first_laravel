<?php

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
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ScrudController;


 // Route::get('/', function () {
 //     return view('welcome');
 // });

 Auth::routes();

 Route::get('/home', 'HomeController@index')->name('home');

 Auth::routes(['verify'=>true]);

 Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

 Route::get('/redirect/{service}', 'SocialController@redirect');

 Route::get('/callback/{service}', 'SocialController@callback');

 Route::get('fill','ScrudController@getOffers');


 Route::group(['prefix' => LaravelLocalization::setLocale()], function() {
    Route::group(['prefix' => 'offers'], function () {
        route::get('create', 'ScrudController@create');
        route::get('all','ScrudController@show');

        route::get('edit/{offer_id}','ScrudController@editOffer');
        route::post('update/{offer_id}','ScrudController@updateOffer')->name('offers.update');
    });
    route::POST('store','ScrudController@store')->name('offers.store');
});
