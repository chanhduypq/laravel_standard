<?php
use App\Http\Controllers\ClassController;
Route::group([
      'middleware' => 'auth'
    ], function() {
    Route::get('/class', [ClassController::class, 'index'])->name('class');
    Route::get('/class/tao-moi', [ClassController::class, 'create'])->name('class.create');
    Route::post('/class/store', [ClassController::class, 'store'])->name('class.store');
    Route::get('/class/chinh-sua/{slug}', [ClassController::class, 'edit'])->name('class.edit');
    Route::put('/class/update/{slug}', [ClassController::class, 'update'])->name('class.update');
    Route::delete('/class/destroy/{slug}', [ClassController::class, 'destroy'])->name('class.destroy');
    }
);




