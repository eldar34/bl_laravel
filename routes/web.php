<?php

use App\Category;

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

Route::get('/blog/category/{slug?}', 'BlogController@category')->name('category');
Route::get('/blog/article/{slug?}', 'BlogController@article')->name('article');

Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>['auth']], function(){
    Route::get('/', 'DashboardController@dashboard')->name('admin.index');
    Route::resource('/category', 'CategoryController', ['as'=>'admin']);
    Route::resource('/article', 'ArticleController', ['as'=>'admin']);
    Route::group(['prefix' => 'user_managment', 'namespace' => 'UserManagment'], function() {
        Route::resource('/user', 'UserController', ['as' => 'admin.user_managment']);
    });
});

/*Route::get('/', function () {
    return view('blog.home');
});*/

Route::get('/', function () {
    $slug = 'books-1409181201';
    $category = Category::where('slug', $slug)->first();

    return view('blog.category', [
        'category' => $category,
        'articles' => $category->articles()->where('published', 1)->orderBy('updated_at', 'desc')->paginate(12)
    ]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
