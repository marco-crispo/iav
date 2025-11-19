<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::redirect('/', '/app');

Route::get('/{any?}', function () {
    $path = public_path('app/index.html');

    if (!File::exists($path)) {
        abort(404, 'Frontend build not found: '.$path);
    }

    return File::get($path);
})->where('any', '.*');


