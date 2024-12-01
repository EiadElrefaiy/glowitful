<?php

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

Route::group(['middleware' => ['api'] ,'prefix' =>'user'] , function(){
    Route::post('register', [App\Http\Controllers\Auth\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::post('gmail-login', [App\Http\Controllers\Auth\AuthController::class, 'loginGmail']);
    Route::post('facebook-login', [App\Http\Controllers\Auth\AuthController::class, 'loginFacebook']);

});

Route::group(['middleware' => ['api', 'CheckUserToken:api-user'] ,'prefix' =>'user'] , function(){
    Route::post('logout', [App\Http\Controllers\Auth\AuthController::class, 'logout']);

    Route::post('update-user', [App\Http\Controllers\User\UpdateUserController::class, 'update']);
    Route::post('update-user-location', [App\Http\Controllers\User\UpdateUserController::class, 'updateLocation']);
    Route::post('read-user', [App\Http\Controllers\User\ReadUserController::class, 'show']);
    Route::get('users', [App\Http\Controllers\User\ReadUserController::class, 'index']);


    Route::post('send-otp', [App\Http\Controllers\Auth\AuthController::class, 'sendOtp']);
    Route::post('phone-verified', [App\Http\Controllers\Auth\AuthController::class, 'phoneVerified']);
    Route::post('forget-password', [App\Http\Controllers\Auth\AuthController::class, 'forgetPassword']);
    Route::post('reset-password', [App\Http\Controllers\Auth\AuthController::class, 'resetPassword']);


    Route::post('service-providers-store', [App\Http\Controllers\ServiceProviders\CreateServiceProviderController::class, 'store']);
    Route::get('service-providers-show', [App\Http\Controllers\ServiceProviders\ReadServiceProviderController::class, 'show']);
    Route::get('service-providers-show-all', [App\Http\Controllers\ServiceProviders\ShowAllProvidersControllers::class, 'show']);
    Route::post('service-providers-show-type', [App\Http\Controllers\ServiceProviders\ShowAllProvidersControllers::class, 'showType']);
    Route::post('service-providers-update', [App\Http\Controllers\ServiceProviders\UpdateServiceProviderController::class, 'update']);
    Route::get('service-providers-delete', [App\Http\Controllers\ServiceProviders\DeleteServiceProviderController::class, 'destroy']);
    
    Route::post('service-providers-search', [App\Http\Controllers\ServiceProviders\SearchAndFiltersController::class, 'search']);
    Route::post('service-providers-filter-price', [App\Http\Controllers\ServiceProviders\FilterController::class, 'filterPrice']);
    Route::post('service-providers-filter-review', [App\Http\Controllers\ServiceProviders\FilterController::class, 'filterReview']);
    Route::post('service-providers-filter-location', [App\Http\Controllers\ServiceProviders\FilterController::class, 'filterLocation']);


    Route::post('service-store', [App\Http\Controllers\Services\CreateServiceController::class, 'store']);
    Route::get('service-show/{id}', [App\Http\Controllers\Services\ReadServiceController::class, 'show']);
    Route::post('service-update', [App\Http\Controllers\Services\UpdateServiceController::class, 'update']);
    Route::get('service-delete/{id}', [App\Http\Controllers\Services\DeleteServiceController::class, 'destroy']);


    Route::post('story_files-store', [App\Http\Controllers\Stories\CreateStoryController::class, 'store']);
    Route::get('story-show/{provider_id}', [App\Http\Controllers\Stories\ShowStoryController::class, 'show']);
    Route::get('story-show-all', [App\Http\Controllers\Stories\ShowAllStroriesController::class, 'showAll']);
    Route::get('story-delete/{id}', [App\Http\Controllers\Stories\DeleteStoryController::class, 'destroy']);


    Route::post('add-favourites', [App\Http\Controllers\Favourites\CreateFavouriteController::class, 'store']);
    Route::get('show-favourites', [App\Http\Controllers\Favourites\ShowFavouritesController::class, 'show']);
    Route::get('show-favourite/{id}', [App\Http\Controllers\Favourites\ShowSingleStoryController::class, 'show']);
    Route::get('delete-favourites/{id}', [App\Http\Controllers\Favourites\DeleteFavouritesController::class, 'destroy']);

    
    Route::post('orders-create', [App\Http\Controllers\Orders\CreateOrderController::class, 'store']);
    Route::post('user-orders-show', [App\Http\Controllers\Orders\ShowUserOrdersController::class, 'show']);
    Route::post('provider-orders-show', [App\Http\Controllers\Orders\ShowProviderOrdersController::class, 'show']);
    Route::get('order/{id}', [App\Http\Controllers\Orders\ShowOrderController::class, 'showSingleOrder']);
    Route::post('update-order/{id}', [App\Http\Controllers\Orders\UpdateOrderStatusController::class, 'update']);
    Route::post('update-order-datetime/{id}', [App\Http\Controllers\Orders\UpdateOrderDateTimeController::class, 'update']);
    Route::get('delete-order/{id}', [App\Http\Controllers\Orders\DeleteOrderController::class, 'destroy']);


    Route::post('add-order-service/{order_id}', [App\Http\Controllers\OrderServices\CreateOrderServicesController::class, 'store']);
    Route::get('delete-order-service/{id}', [App\Http\Controllers\OrderServices\DeleteOrderServiceController::class, 'destroy']);

    Route::post('add-request-service', [App\Http\Controllers\Requests\CreateRequestController::class, 'create']);
    Route::get('show-request-service/{id}', [App\Http\Controllers\Requests\ShowRequestController::class, 'show']);
    Route::post('show-users-request', [App\Http\Controllers\Requests\ShowUserRequestsController::class, 'show']);
    Route::post('show-providers-request', [App\Http\Controllers\Requests\showProviderRequestsController::class, 'show']);
    Route::post('update-request/{id}', [App\Http\Controllers\Requests\UpdateStatusController::class, 'updateStatus']);
    Route::post('update-request-datetime/{id}', [App\Http\Controllers\Requests\UpdateRequestDateTimeController::class, 'updateDateTime']);
    Route::post('delete-request/{id}', [App\Http\Controllers\Requests\DeleteStatusController::class, 'destroy']);


    Route::post('create-message', [App\Http\Controllers\Messages\CreateMessageController::class, 'store']);
    Route::get('read-message/{id}', [App\Http\Controllers\Messages\ReadMessageController::class, 'show']);
    Route::post('read-messages-receiver', [App\Http\Controllers\Messages\ShowUserMessagesController::class, 'showRecived']);
    Route::post('read-messages-sender', [App\Http\Controllers\Messages\ShowUserMessagesController::class, 'showSend']);
    Route::post('update-message/{id}', [App\Http\Controllers\Messages\UpdateMessageController::class, 'update']);
    Route::get('delete-message/{id}', [App\Http\Controllers\Messages\DeleteMessageController::class, 'delete']);



    Route::post('order-map/{id}', [App\Http\Controllers\Map\MapController::class, 'show']);

});
