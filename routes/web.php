<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController; 
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

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
//リソースルートの定義
Route::resource('products', ProductController::class);

// 商品リストの表示
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

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
