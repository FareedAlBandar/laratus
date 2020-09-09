<?php

use Illuminate\Support\Facades\Route;

Route::match(['get', 'options'], '/tus/{any?}', function () {
    $response = app('tus-server')->serve();
    
    return $response->send();
})->where('any', '.*')->middleware(config('laratus.down_middleware'));

Route::match(['post', 'put', 'patch', 'delete'], '/tus/{any?}', function () {
    $response = app('tus-server')->serve();
    
    return $response->send();
})->where('any', '.*')->middleware(config('laratus.up_middleware'));
