<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoritelistController;
use App\Http\Controllers\OrderController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('registerpharmacist', 'registerPharmacist');
});

    Route::group(["middleware" => ["auth:api"]], function(){
    


    Route::group(["middleware" => ["check_Admin"]], function(){
    Route::post('Add_category', [CategoryController::class, 'store']);
    Route::get('show_category', [CategoryController::class, 'show']);
    Route::post('update_category/{id}', [CategoryController::class, 'update']);
    Route::delete('destroy_category/{id}', [CategoryController::class, 'destroy']);
    });

    Route::group(["middleware" => ["check_Admin"]], function(){
    Route::post('create_medicine', [MedicineController::class, 'create_Medecine']);
    Route::get('show_all_medecine', [MedicineController::class, 'ShowAllmedecines']);
    Route::get('preparation/{id}', [OrderController::class, 'In_preparation']);
    Route::get('show_orders', [OrderController::class, 'showordars']);
    Route::get('HasSent/{id}', [OrderController::class, 'Has_sent']);
    Route::post('ItReceived/{id}', [OrderController::class, 'IT_Received']);
    });


    Route::group(["middleware" => ["check_Pharmacist"]], function(){
    Route::post('create_order', [OrderController::class, 'createorder']);
    Route::get('show_order', [OrderController::class, 'showordar']);
    Route::post('AddToFavorite', [FavoritelistController::class, 'AddToFavorite']);
    Route::delete('RemoveFromFavorit/{medicine_id}', [FavoritelistController::class, 'RemoveFromFavorite']);
    Route::get('showfavorite', [FavoritelistController::class, 'showFav']);
    });

    Route::get('searchPN/{name}', [MedicineController::class, 'search_name']);
    Route::get('show_single_medecine/{id}', [MedicineController::class, 'showsingle']);
    Route::get('profile', [AuthController::class, 'profile']);

});


