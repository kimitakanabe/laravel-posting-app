<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [PostController::class, 'index'])->middleware(['auth', 'verified'])->name('posts.index');


/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/

require __DIR__.'/auth.php';

/*
Route::get('/posts', [PostController::class, 'index'])->middleware(['auth', 'verified'])->name('posts.index');

Route::get('/posts/create', [PostController::class, 'create'])->middleware(['auth', 'verified'])->name('posts.create');

Route::post('/posts', [PostController::class, 'store'])->middleware(['auth', 'verified'])->name('posts.store');

Route::get('/posts/{post}', [PostController::class, 'show'])->middleware(['auth', 'verified'])->name('posts.show');

Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->middleware(['auth', 'verified'])->name('posts.edit');

Route::patch('/posts/{post}', [PostController::class, 'update'])->middleware(['auth', 'verified'])->name('posts.update');

Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware(['auth', 'verified'])->name('posts.destroy');
*/

//LaravelではRoute::resource('基準となるURL', コントローラ名::class);と記述することで、各アクションのルーティングを自動で一括設定することができる
Route::resource('posts', PostController::class)->middleware(['auth', 'verified']);
