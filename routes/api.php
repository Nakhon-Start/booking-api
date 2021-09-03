<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ResponsibilitiesController;
use App\Models\Booking;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'Register'])->name('register');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'Login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'User']);
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'Logout'])->name('logout');
    Route::get('/listusers', [\App\Http\Controllers\AuthController::class, 'ListUsers']);
    Route::get('/getuser/{id}', [\App\Http\Controllers\AuthController::class, 'GetUser']);
    Route::put('/setuser', [\App\Http\Controllers\AuthController::class, 'SetUser']);

//    Route::get('/room', [RoomController::class, 'GetListRoom']);
//    Route::get('/room/{id}', [RoomController::class, 'GetRoom']);
    Route::post('/createroom', [RoomController::class, 'CreateRoom']);
    Route::put('/setroom', [RoomController::class, 'SetRoom']);

//    Route::get('/building', [BuildingController::class, 'GetlistBuilding']);
//    Route::get('/building/{id}', [BuildingController::class, 'GetBuilding']);
    Route::put('/setbuilding', [BuildingController::class, 'SetBuilding']);
    Route::post('/createtbuilding', [BuildingController::class, 'CreateBuilding']);


    Route::post('/booking', [BookingController::class, 'Booking'])->name('booking');
    Route::post('/approve', [BookingController::class, 'Approve']);
    Route::get('/history', [BookingController::class, 'History']);
    //Route::get('/booking', [BookingController::class, 'Show']);
    //Route::get('/booking/{id}', [BookingController::class, 'ShowBooking']);

    Route::post('/createresponsibilities', [ResponsibilitiesController::class, 'CreateResponsibilities']);
    Route::post('/getcheckerformbuildingid', [ResponsibilitiesController::class, 'GetCheckerFromBuildingID']);
    Route::post('/getbuildingformuserid', [ResponsibilitiesController::class, 'GetBuildingFormUserID']);  
    Route::get('/getbuildingformcheckerid', [ResponsibilitiesController::class, 'GetBuildingFormCheckerID']);


    Route::put('/setbooking', [BookingController::class, 'SetBooking']);
    Route::put('/cancle', [BookingController::class, 'Cancle']);


});



Route::post('/getroomlistbooking', [RoomController::class, 'GetRoomListBooking']);
Route::post('/getroomlistbookingbydate', [RoomController::class, 'GetRoomListBookingByDate']);


Route::get('/getlistroom', [RoomController::class, 'GetListRoom']);
Route::get('/getroom/{id}', [RoomController::class, 'GetRoom']);


Route::get('/getlistbuilding', [BuildingController::class, 'GetlistBuilding']);
Route::get('/getbuilding/{id}', [BuildingController::class, 'GetBuilding']);

Route::get('/getlistbooking', [BookingController::class, 'ShowListBooking']);
Route::get('/getbooking/{id}', [BookingController::class, 'ShowBooking']);