<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/calendar',['uses' =>'EventsController@calendar'])->name('calendar');

Route::post('/save-event',['uses' =>'EventsController@save_event'])->name('save_event');

Route::post('/edit-event',['uses' =>'EventsController@edit_event'])->name('edit_event');