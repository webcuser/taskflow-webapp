<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app' => 'TaskFlow API',
        'status' => 'ok',
        'endpoints' => ['/api/register', '/api/login', '/api/logout'],
    ]);
});
