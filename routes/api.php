<?php

use App\Http\Controllers\DocumentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use League\CommonMark\Block\Element\Document;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/documents', [DocumentsController::class, 'index']);
// Route::post('/documents', [DocumentsController::class, 'store']);
Route::resource('documents', DocumentsController::class);
Route::get('/documents/search/{document_name}', [DocumentsController::class, 'search']);
