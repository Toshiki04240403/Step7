<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController; 
use App\Http\Controllers\ProductController;

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

Route::get('/products/{id}/edit', 'ProductController@edit')->name('products.edit');
Route::put('/products/{id}', 'ProductController@update')->name('products.update');


// 商品リストの表示
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::post('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

// 販売ページの表示
Route::get('/sales', [ProductController::class, 'showsales'])->name('sales.index');

// 詳細ページの表示
Route::get('/show', [ProductController::class, 'show'])->name('show.index');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// 会員登録ページの表示
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ログインページの表示
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// ログアウト
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ホームページの表示
Route::view('/home', 'home');

//新規登録ルート
Route::post('/sales', [ProductController::class, 'store'])->name('sales.index');
// 新規登録後のリダイレクト
Route::get('/sales', [ProductController::class, 'showSalesView'])->name('sales.view');

Route::get('/sales', [ProductController::class, 'create'])->name('products.create');

