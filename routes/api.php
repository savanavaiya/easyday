<?php

use App\Http\Controllers\AppuserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/addnumber/sendotp', [HomeController::class,'store']);

Route::post('/otp/verification', [HomeController::class,'veriotp']);


Route::post('/create/user', [HomeController::class,'creuser']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/edit/profile', [HomeController::class,'editpro']);

    Route::post('/create/project', [ProjectController::class,'store']);

    Route::get('/user/project', [ProjectController::class,'getusepro']);

    Route::get('/project/targetingseparate', [ProjectController::class,'getpro']);

    // Route::post('/project/add_participates', [ProjectController::class,'addpart']);

    Route::get('/project/get_participates', [ProjectController::class,'gepart']);

    

    Route::post('/project/add_attributes', [ProjectController::class,'addattr']);

    Route::get('/project/get_attributes', [ProjectController::class,'getattr']);

    Route::delete('/project/delete', [ProjectController::class,'delproject']);

    Route::put('/project/editdata', [ProjectController::class,'ediproject']);

    Route::post('project/add_task', [TaskController::class,'store']);

    Route::get('project/get_task', [TaskController::class,'gettask']);

    Route::post('project/task/discussion/add_comments', [TaskController::class,'addcomm']);

    Route::get('project/task/discussion/get_comments', [TaskController::class,'getcomm']);

    Route::get('project/task/discussion/comments/like', [TaskController::class,'likecom']);

    Route::get('project/task/discussion/comments/like/record', [TaskController::class,'likecomrec']);

    Route::get('project/task/discussion/comments/like/return', [TaskController::class,'likecomreturn']);

    Route::get('/logout/user', [HomeController::class,'logout']);

    Route::delete('/user/delete', [HomeController::class,'deleteuserac']);
});
