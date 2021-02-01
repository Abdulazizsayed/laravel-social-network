<?php

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect("/home");
    }
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('posts', 'PostController');
    Route::resource('comments', 'CommentController');
    Route::resource('replies', 'ReplyController');
    Route::resource('messages', 'MessageController');
    Route::post('/like', 'LikeController@toggleLike');
    // Toggle follow
    Route::post('users/toggleFollow/{user}', 'UserController@toggleFollow');
    // Search
    Route::post('users/searchFollowed', 'UserController@searchFollowed');
    Route::post('users/searchUnfollowed', 'UserController@searchUnfollowed');
    // Show profile
    Route::get('users/profile/{user}', 'ProfileController@show');
    // Edit my profile
    Route::get('users/editProfile', 'ProfileController@edit');
    // Update my profile
    Route::post('users/updateProfile', 'ProfileController@update')->name('users.updateProfile');
    // Get message me and another user
    Route::get('users/messages/{user}', 'MessageController@index')->name('users.messages');
});
