<?php

use App\Http\Controllers\AccessTokensController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ResponsibilitiesController;




Route::post('/register', [\App\Http\Controllers\AuthController::class, 'Register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'Login']);

Route::get('/getchecker', [ResponsibilitiesController::class, 'GetChecker']);
Route::post('/test', [BookingController::class, 'test']);


Route::post('/getroomlistbooking', [RoomController::class, 'GetRoomListBooking']);
Route::post('/getroomlistbookingbydate', [RoomController::class, 'GetRoomListBookingByDate']);

Route::get('/getlistroom', [RoomController::class, 'GetListRoom']);
Route::get('/getroom/{id}', [RoomController::class, 'GetRoom']);

Route::post('/SearchForRoomsByTime', [RoomController::class, 'SearchForRoomsByTime']);
Route::post('/SearchForAvailableRoom', [RoomController::class, 'SearchForAvailableRoom']);




Route::get('/getlistbuilding', [BuildingController::class, 'GetlistBuilding']);
Route::post('/getbuilding', [BuildingController::class, 'GetBuilding']);

Route::get('/getlistbooking', [BookingController::class, 'ShowListBooking']);
Route::get('/getbooking/{id}', [BookingController::class, 'ShowBooking']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checkerApprove', [BookingController::class, 'checkerApprove']);
    Route::get('/onlineUser', [AccessTokensController::class, 'onlineUser']);
    Route::get('/getcheckerId', [ResponsibilitiesController::class, 'getResponsibilities']);
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'User']);
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'Logout']);
    Route::get('/listusers', [\App\Http\Controllers\AuthController::class, 'ListUsers']);
    Route::get('/getuser/{id}', [\App\Http\Controllers\AuthController::class, 'GetUser']);
    Route::put('/setuser', [\App\Http\Controllers\AuthController::class, 'SetUser']);
    Route::post('/createroom', [RoomController::class, 'CreateRoom']);
    Route::put('/setroom', [RoomController::class, 'SetRoom']);
    Route::put('/setbuilding', [BuildingController::class, 'SetBuilding']);
    Route::post('/createtbuilding', [BuildingController::class, 'CreateBuilding']);
    Route::post('/booking', [BookingController::class, 'Booking']);
    Route::post('/approve', [BookingController::class, 'Approve']);
    Route::get('/checkingHistory', [BookingController::class, 'checkingHistory']);
    Route::get('/bookerHistory', [BookingController::class, 'bookerHistory']);
    Route::post('/createresponsibilities', [ResponsibilitiesController::class, 'CreateResponsibilities']);
    Route::post('/getcheckerformbuildingid', [ResponsibilitiesController::class, 'GetCheckerFromBuildingID']);
    Route::post('/getbuildingformuserid', [ResponsibilitiesController::class, 'GetBuildingFormUserID']);
    Route::get('/getbuildingformcheckerid', [ResponsibilitiesController::class, 'GetBuildingFormCheckerID']);
    Route::put('/setbooking', [BookingController::class, 'SetBooking']);
    Route::put('/cancle', [BookingController::class, 'Cancle']);
});

