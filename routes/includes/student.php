<?php
use App\Http\Controllers\StudentController;
Route::group([
      'middleware' => 'auth'
    ], function() {
        Route::any('/sinh-vien', [StudentController::class, 'index'])->name('student');
        Route::get('/sinh-vien/tao-moi', [StudentController::class, 'create'])->name('student.create');
        Route::post('/sinh-vien/store', [StudentController::class, 'store'])->name('student.store');
        Route::get('/sinh-vien/chinh-sua/{slug}', [StudentController::class, 'edit'])->name('student.edit');
        Route::put('/sinh-vien/update/{slug}', [StudentController::class, 'update'])->name('student.update');
        Route::delete('/sinh-vien/destroy/{slug}', [StudentController::class, 'destroy'])->name('student.destroy');
        Route::get('/sinh-vien/download/{slug}', [StudentController::class, 'download'])->name('student.download');
    }
);


