<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RtbController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/rtb', [RtbController::class, 'handleBidRequest']);

