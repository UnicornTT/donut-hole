<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\DepartmentController;
use \App\Http\Controllers\EmployeeController;

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

Route::get('/', function () {
    return response()->file(resource_path('api_documentation.json'));
});

Route::get('/department', [\App\Http\Controllers\DepartmentController::class, 'get']);
Route::post('/department/create', [\App\Http\Controllers\DepartmentController::class, 'create']);
Route::put('/department/update/{id}', [\App\Http\Controllers\DepartmentController::class, 'update']);
Route::delete('/department/delete/{id}', [\App\Http\Controllers\DepartmentController::class, 'delete']);

Route::get('/employee', [\App\Http\Controllers\EmployeeController::class, 'get']);
Route::post('/employee/create', [\App\Http\Controllers\EmployeeController::class, 'create']);
Route::put('/employee/update/{id}', [\App\Http\Controllers\EmployeeController::class, 'update']);
Route::delete('/employee/delete/{id}', [\App\Http\Controllers\EmployeeController::class, 'delete']);
