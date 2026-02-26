<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('{{moduleLower}}')
    ->group(function () {
        Route::get('/', 
            [\Modules\{{module}}\Http\Controllers\{{module}}Controller::class, 'index']
        );
    });