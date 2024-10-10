<?php

use Illuminate\Support\Facades\Route;

// This is done only to simply testing, in case someone will hit the URL via browser
$API_ONLY_MESSAGE = "This is a test API ONLY application. Please authenticate yourself first via HTTP POST request to " . url('api/login') . ". You must include 'Accept: application/json' header in your requests.";

Route::get('/', function () use ($API_ONLY_MESSAGE) {
    return response()->json(['message' => $API_ONLY_MESSAGE]);
});

Route::get('/login', function () use ($API_ONLY_MESSAGE) {
    return response()->json(['message' => $API_ONLY_MESSAGE]);
});

