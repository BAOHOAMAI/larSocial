<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use Inertia\Inertia;

Route::get('/',  [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');
Route::get('/u/{user:username}',  [ProfileController::class, 'index'])->name('profile');

Route::middleware('auth')->group(function () {
    
    Route::post('/profile/update-images', [ProfileController::class, 'updateImages'])
            ->name('profile.updateImages');
    Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/post', [PostController::class, 'store'])->name('post');
    Route::put('/post/{post}', [PostController::class, 'update'])
            ->name('post.update');
    Route::delete('/post/{post}', [PostController::class, 'delete'])
            ->name('post.delete');
    Route::post('/{post}/reaction', [PostController::class, 'postReaction'])
            ->name('post.reaction');
    Route::get('download/{attachment}', [PostController::class, 'downloadFile'])
            ->name('post.download');
    Route::post('/{post}/comment', [PostController::class, 'postComment'])
            ->name('post.comment.create');
    Route::post('/comment/{comment}', [PostController::class, 'updateComment'])
            ->name('comment.update');
    Route::delete('/comment/{comment}', [PostController::class, 'deleteComment'])
            ->name('comment.delete');
    Route::post('/comment/{comment}/reaction', [PostController::class, 'commentReaction'])
            ->name('comment.reaction');
}); 

require __DIR__.'/auth.php';
