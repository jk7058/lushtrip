<?php
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
use Illuminate\Support\Facades\Route;
use Modules\Page\Controllers\PageController;

Route::get('/', 'HomeController@index');

Route::get('/{slug?}', 'PageController@detail')
    ->where('slug', '^(?!admin|login|user|vendor|location|api-admin)([a-zA-Z0-9\-_/]*)?$')
    ->name('page.detail');