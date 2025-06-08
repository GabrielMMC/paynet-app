<?php

use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/users/process', [UserController::class, 'processUser']);
    Route::get('/users/{cpf}', [UserController::class, 'findUser']);
});
