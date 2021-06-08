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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/password/change', 'Auth\ChangePasswordController@change')->name('password.change');
Route::post('/password/change', 'Auth\ChangePasswordController@change_do');

Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('admin')->group(function () {

	Route::get('', 'AdminController@index')->name('dashboard');

	/* Role */
	Route::get('roles', 'RoleController@browse')->name('roles');
	Route::get('roles/add', 'RoleController@add')->name('roles.add');
	Route::get('roles/{id}/edit', 'RoleController@edit')->name('roles.edit');
	Route::get('roles/{id}/delete', 'RoleController@delete')->name('roles.delete');
	Route::get('roles/{id}/attach', 'RoleController@attach')->name('roles.attach');

	Route::post('roles/add', 'RoleController@add_do');
	Route::post('roles/{id}/edit', 'RoleController@edit_do');
	Route::post('roles/{id}/delete', 'RoleController@delete_do');
	Route::post('roles/{id}/attach', 'RoleController@attach_do');

	/* User */
	Route::get('users', 'UserController@browse')->name('users');
	Route::get('users/add', 'UserController@add')->name('users.add');
	Route::get('users/{id}/edit', 'UserController@edit')->name('users.edit');
	Route::get('users/{id}/delete', 'UserController@delete')->name('users.delete');

	Route::post('users/add', 'UserController@add_do');
	Route::post('users/{id}/edit', 'UserController@edit_do');
	Route::post('users/{id}/delete', 'UserController@delete_do');

	/* MenuItem */
	Route::get('menu_items', 'MenuItemController@browse')->name('menu_items');
	Route::get('menu_items/add', 'MenuItemController@add')->name('menu_items.add');
	Route::get('menu_items/{id}/edit', 'MenuItemController@edit')->name('menu_items.edit');
	Route::get('menu_items/{id}/delete', 'MenuItemController@delete')->name('menu_items.delete');

	Route::post('menu_items/add', 'MenuItemController@add_do');
	Route::post('menu_items/{id}/edit', 'MenuItemController@edit_do');
	Route::post('menu_items/{id}/delete', 'MenuItemController@delete_do');

	/* File */
	Route::get('files', 'FileController@browse')->name('files');
	Route::get('files/add', 'FileController@add')->name('files.add');
	Route::get('files/{id}/edit', 'FileController@edit')->name('files.edit');
	Route::get('files/{id}/delete', 'FileController@delete')->name('files.delete');

	Route::post('files/add', 'FileController@add_do');
	Route::post('files/{id}/edit', 'FileController@edit_do');
	Route::post('files/{id}/delete', 'FileController@delete_do');

	/* Category */
	Route::get('categories', 'CategoryController@browse')->name('categories');
	Route::get('categories/add', 'CategoryController@add')->name('categories.add');
	Route::get('categories/{id}/edit', 'CategoryController@edit')->name('categories.edit');
	Route::get('categories/{id}/delete', 'CategoryController@delete')->name('categories.delete');

	Route::post('categories/add', 'CategoryController@add_do');
	Route::post('categories/{id}/edit', 'CategoryController@edit_do');
	Route::post('categories/{id}/delete', 'CategoryController@delete_do');

	/* Post */
	Route::get('posts', 'PostController@browse')->name('posts');
	Route::get('posts/add', 'PostController@add')->name('posts.add');
	Route::get('posts/{id}/edit', 'PostController@edit')->name('posts.edit');
	Route::get('posts/{id}/delete', 'PostController@delete')->name('posts.delete');

	Route::post('posts/add', 'PostController@add_do');
	Route::post('posts/{id}/edit', 'PostController@edit_do');
	Route::post('posts/{id}/delete', 'PostController@delete_do');

	/* Link */
	Route::get('links', 'LinkController@browse')->name('links');
	Route::get('links/add', 'LinkController@add')->name('links.add');
	Route::get('links/{id}/edit', 'LinkController@edit')->name('links.edit');
	Route::get('links/{id}/delete', 'LinkController@delete')->name('links.delete');

	Route::post('links/add', 'LinkController@add_do');
	Route::post('links/{id}/edit', 'LinkController@edit_do');
	Route::post('links/{id}/delete', 'LinkController@delete_do');

	/* AJAX Routes*/
	Route::get('users/ajax/browse', 'Ajax\UserController@browse');
	Route::get('posts/ajax/browse', 'Ajax\PostController@browse');
	Route::get('posts/ajax/attachments', 'Ajax\PostController@getAttachments');
	Route::get('posts/ajax/translation', 'Ajax\PostController@getTranslation');
	Route::get('links/ajax/parents', 'Ajax\LinkController@getParents');

});
