<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('encrypted-file.upload-file');
});

Route::post('/file-upload', [FileController::class, 'file_upload']);
Route::post('/decrypt-file', [FileController::class, 'decrypt_file']);
Route::post('/encrypt-file', [FileController::class, 'encrypt_file']);
