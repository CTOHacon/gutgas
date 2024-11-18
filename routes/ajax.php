<?php

use App\Http\Controllers\Ajax\MediaFileController;
use App\Http\Controllers\Ajax\RequiredProductsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'ajax.',
    'middleware' => ['web'],
], function () {
    Route::controller(MediaFileController::class)->prefix('mediaFile')->as('mediaFile.')
        ->group(
            function () {
                Route::get('', 'index')->name('index')
                    ->middleware('permission:ajax.mediaFile.index');
                Route::post('/store', 'store')->name('store')->middleware('permission:ajax.mediaFile.manage');

                Route::delete('/delete/{mediaFile}', 'destroy')->name('destroy')->middleware('permission:ajax.mediaFile.manage');
            }
        );

    Route::get('/getRequiredProducts', [RequiredProductsController::class, 'getRequiredProducts'])->name('getRequiredProducts');
});
