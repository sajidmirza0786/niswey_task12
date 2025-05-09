<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('welcome');
});



Route::controller(ContactController::class)->group(function(){
    Route::post('upload', 'upload')->name('contacts.upload');
    Route::get('/contacts/recover/{id}', 'recover')->name('contacts.recover');
    Route::delete('contacts/{contact}/force', 'forceDelete')->name('contacts.forceDelete');

});
Route::resource('contacts', ContactController::class);