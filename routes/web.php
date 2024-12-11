<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

use App\Http\Controllers\PDFController;

Route::get('/generate-pdf-preview', [PDFController::class, 'preview'])->name('generate.pdf.preview');
