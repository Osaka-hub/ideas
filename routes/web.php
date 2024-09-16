<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::group([ 'prefix'=>'ideas/' ], function () {

    Route::get('/{idea}', [IdeaController::class, 'show'])->name('ideas.show');

    Route::post('', [IdeaController::class, 'store'])->name('idea.store');


    Route::group([ 'middleware' => ['auth'] ], function (){

        Route::get('/{idea}/edit', [IdeaController::class, 'edit'])->name('ideas.edit');

        Route::put('/{idea}', [IdeaController::class, 'update'])->name('ideas.update');

        Route::delete('/{idea}', [IdeaController::class, 'destroy'])->name('idea.destroy');

        Route::post('/{idea}/comments', [CommentController::class, 'store'])->name('ideas.comments.store');

    });

});

Route::get('profile', [UserController::class,'profile'])->middleware('auth')->name('profile');

Route::resource('users', UserController::class)->only('show','edit','update')->middleware('auth');

Route::post('users/{user}/follow',[FollowerController::class,'follow'])->middleware('auth')->name('users.follow');
Route::post('users/{user}/unfollow',[FollowerController::class,'unfollow'])->middleware('auth')->name('users.unfollow');


Route::get('/terms', function(){
    return view('terms');
})->name('terms');
