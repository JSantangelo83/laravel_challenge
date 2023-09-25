<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\AuthController@user');
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('/refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
});

Route::prefix('actors')->group(function () {
    Route::get('/', 'App\Http\Controllers\ActorController@index');
    Route::post('/', 'App\Http\Controllers\ActorController@store');
    Route::get('/{actor}', 'App\Http\Controllers\ActorController@show');
    Route::put('/{actor}', 'App\Http\Controllers\ActorController@update');
    Route::delete('/{actor}', 'App\Http\Controllers\ActorController@destroy');
});

Route::prefix('directors')->group(function () {
    Route::get('/', 'App\Http\Controllers\DirectorController@index');
    Route::post('/', 'App\Http\Controllers\DirectorController@store');
    Route::get('/{director}', 'App\Http\Controllers\DirectorController@show');
    Route::put('/{director}', 'App\Http\Controllers\DirectorController@update');
    Route::delete('/{director}', 'App\Http\Controllers\DirectorController@destroy');
});

Route::prefix('movies')->group(function () {
    Route::get('/', 'App\Http\Controllers\MovieController@index');
    Route::post('/', 'App\Http\Controllers\MovieController@store');
    Route::get('/{movie}', 'App\Http\Controllers\MovieController@show');
    Route::put('/{movie}', 'App\Http\Controllers\MovieController@update');
    Route::delete('/{movie}', 'App\Http\Controllers\MovieController@destroy');
});

Route::prefix('age-classifications')->group(function () {
    Route::get('/', 'App\Http\Controllers\AgeClassificationController@index');
    Route::post('/', 'App\Http\Controllers\AgeClassificationController@store');
    Route::get('/{ageClassification}', 'App\Http\Controllers\AgeClassificationController@show');
    Route::put('/{ageClassification}', 'App\Http\Controllers\AgeClassificationController@update');
    Route::delete('/{ageClassification}', 'App\Http\Controllers\AgeClassificationController@destroy');
});

Route::prefix('tvshows')->group(function () {
    Route::get('/', 'App\Http\Controllers\TvShowController@index');
    Route::post('/', 'App\Http\Controllers\TvShowController@store');
    Route::get('/{tvShow}', 'App\Http\Controllers\TvShowController@show');
    Route::put('/{tvShow}', 'App\Http\Controllers\TvShowController@update');
    Route::delete('/{tvShow}', 'App\Http\Controllers\TvShowController@destroy');

    Route::get('/{tvShow}/seasons', 'App\Http\Controllers\TvShowSeasonController@index');
    Route::post('/{tvShow}/seasons', 'App\Http\Controllers\TvShowSeasonController@store');
    Route::get('/{tvShow}/seasons/{season}', 'App\Http\Controllers\TvShowSeasonController@show');
    Route::put('/{tvShow}/seasons/{season}', 'App\Http\Controllers\TvShowSeasonController@update');
    Route::delete('/{tvShow}/seasons/{season}', 'App\Http\Controllers\TvShowSeasonController@destroy');

    Route::get('/{tvShow}/seasons/{season}/episodes', 'App\Http\Controllers\TvShowEpisodeController@index');
    Route::post('/{tvShow}/seasons/{season}/episodes', 'App\Http\Controllers\TvShowEpisodeController@store');
    Route::get('/{tvShow}/seasons/{season}/episodes/{episode}', 'App\Http\Controllers\TvShowEpisodeController@show');
    Route::put('/{tvShow}/seasons/{season}/episodes/{episode}', 'App\Http\Controllers\TvShowEpisodeController@update');
    Route::delete('/{tvShow}/seasons/{season}/episodes/{episode}', 'App\Http\Controllers\TvShowEpisodeController@destroy');
});