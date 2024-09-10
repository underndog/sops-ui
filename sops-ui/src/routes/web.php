<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('encrypted-file.upload-encrypted-file');
});

Route::get('/adjust-encrypted-file', function () {
    return view('encrypted-file.upload-encrypted-file');
});

Route::post('/file-upload', [FileController::class, 'file_upload']);
Route::post('/decrypt-file', [FileController::class, 'decrypt_file']);
Route::post('/encrypt-file', [FileController::class, 'encrypt_file']);


Route::get('/upload-file-raw', [FileController::class, 'upload_file_raw']);
Route::post('/encrypt-file-raw', [FileController::class, 'encrypt_file_raw']);

