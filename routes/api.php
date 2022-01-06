<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IntegracaoController;
use App\Http\Controllers\Api\TagsController;
use App\Http\Controllers\Api\ContactsController;
use App\Http\Controllers\Api\JwtAuthController;


Route::post('login', [JwtAuthController::class,'login']);
Route::post('register', [JwtAuthController::class,'register']);

Route::group(['middleware' => 'jwt.verify'], function () {

    Route::post('logout', [JwtAuthController::class,'logout']);
    Route::get('me', [JwtAuthController::class,'me']);

    Route::get('messages', [IntegracaoController::class,'messages']);
    Route::get('sessions', [IntegracaoController::class,'sessions']);
    Route::post('sendText', [IntegracaoController::class,'sendText']);

    Route::get('contacts/show', [ContactsController::class,'show']);
    Route::post('contacts/store', [ContactsController::class,'store']);
    Route::put('contacts/update', [ContactsController::class,'update']);

    Route::get('tags/show', [TagsController::class,'show']);
    Route::post('tags/store', [TagsController::class,'store']);
    Route::put('tags/update', [TagsController::class,'update']);
    Route::delete('tags/destroy', [TagsController::class,'destroy']);

});
