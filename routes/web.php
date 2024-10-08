<?php

use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\IdeaController as AdminIdeaController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaLikeController;
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

Route::resource('users', UserController::class)->only('edit','update')->middleware('auth');
Route::resource('users', UserController::class)->only('show');

Route::post('users/{user}/follow',[FollowerController::class,'follow'])->middleware('auth')->name('users.follow');
Route::post('users/{user}/unfollow',[FollowerController::class,'unfollow'])->middleware('auth')->name('users.unfollow');

Route::post('ideas/{idea}/like',[IdeaLikeController::class,'like'])->middleware('auth')->name('ideas.like');
Route::post('ideas/{idea}/unlike',[IdeaLikeController::class,'unlike'])->middleware('auth')->name('ideas.unlike');

Route::get('/feed', FeedController::class)->middleware('auth')->name('feed');

Route::get('/terms', function(){
    return view('terms');
})->name('terms');

Route::middleware(['auth','can:admin'])->prefix('/admin')->as('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->only('index');

    Route::resource('ideas', AdminIdeaController::class)->only('index');

    Route::resource('comments', AdminCommentController::class)->only('index', 'destroy');
});
