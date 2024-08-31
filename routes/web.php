<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('users.index');
});

Route::get('users/get-data', [UserController::class, 'getData'])->name('users.get-data');
Route::resource('users', UserController::class);