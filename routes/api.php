<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\UpdateInfoParentController;
use App\Http\Controllers\UpdateInfoBabysitterController;
use App\Http\Controllers\ParentController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/


Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/loginbb', 'App\Http\Controllers\AuthController@loginbb');

    Route::post('/signup', 'App\Http\Controllers\AuthController@signup');
    Route::post('/babysitterlogin', 'App\Http\Controllers\AuthController@babysitterlogin');

    Route::post('/sendPasswordResetEmail', 'App\Http\Controllers\PasswordResetRequestController@sendEmail');
    Route::post('/reset-password', 'App\Http\Controllers\ChangePasswordController@resetPassword');


    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');

    Route::get('/candidates', 'App\Http\Controllers\CandidatesController@candidateprofile');
    Route::get('/candidates/search', 'App\Http\Controllers\CandidatesController@candidateprofile');
    Route::get('/parents/{id}', 'App\Http\Controllers\API\ParentController@getParentById');
    Route::get('/parents/all/ids', 'App\Http\Controllers\API\ParentController@getAllParents');



    Route::put('updateparent/{id}', 'App\Http\Controllers\UpdateInfoParentController@updateparent');

    Route::put('updatebabysitter/{id}', [UpdateInfoBabysitterController::class, 'updatebabysitter']);


    // Resource route for OffreController except for store
    // Here we removed the store route from resouce just to add the middleware
    Route::resource('offre', OffreController::class)->except(['store']);
    // Resource route for OffreController store
    Route::post('/offre', [OffreController::class, 'store'])->name('offre.store')->middleware('auth:user');



    // Custom route for updating an Offre
    Route::put('/offre/{offre}', [OffreController::class, 'update'])->name('offre.update');

    // Custom route for fetching an Offre for editing
    Route::get('/offre/{offre}/edit', [OffreController::class, 'edit'])->name('offre.edit');


    // Inbox routes

    Route::group([
        'prefix' => 'inbox',
        'middleware' => 'auth:user',
    ], function ($router) {
        Route::get('/', 'App\Http\Controllers\API\InboxController@index');
        Route::get('/{receiver}', 'App\Http\Controllers\API\InboxController@show');
        Route::post('/{receiver}', 'App\Http\Controllers\API\InboxController@store');
    });

    Route::group([
        'prefix' => '/bb/inbox',
        'middleware' => 'auth:babysitter',
    ], function ($router) {
        Route::get('/', 'App\Http\Controllers\API\InboxController@index');
        Route::get('/{receiver}', 'App\Http\Controllers\API\InboxController@showbb');
        Route::post('/{receiver}', 'App\Http\Controllers\API\InboxController@storebb');
    });
});
