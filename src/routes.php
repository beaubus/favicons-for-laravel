<?php

/*
|--------------------------------------------------------------------------
| BEAUBUS Favicons routes
|--------------------------------------------------------------------------
|
| Here are all the routes defined that are needed for the BEAUBUS Favicons package.
|
*/

Route::get('favicon.svg', function () {
    return response(file_get_contents(storage_path('app/public/beaubus-favicons/favicon.svg')), 200)
        ->header('Content-Type', 'image/svg+xml');
});