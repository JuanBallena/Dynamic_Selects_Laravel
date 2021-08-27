<?php

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

Route::resource('jobs', '\App\Http\Controllers\JobController');
Route::resource('countries', '\App\Http\Controllers\CountryController');
Route::post('/update-status-visible', '\App\Http\Controllers\CountryController@updateStatusVisible')->name('countries.update.status');

Route::resource('states', '\App\Http\Controllers\StateController');
Route::resource('cities', '\App\Http\Controllers\CityController');
Route::post('/redirect-search', '\App\Http\Controllers\JobController@redirectSearch')->name('jobs.redirectSearch');
Route::get('/place-descriptions', '\App\Http\Controllers\JobController@getPlaceDescriptions')->name('jobs.getPlaceDescriptions');
Route::get('/places', '\App\Http\Controllers\JobController@getPlaces')->name('jobs.getPlaces');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
