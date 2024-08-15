<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RtbController;

Route::post('/rtb', [RtbController::class, 'handleBidRequest']);

