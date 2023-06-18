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


Auth::routes();

Route::prefix('staff-backend')->group(function () {

    // - auth
    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login.post');
    Route::post('logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
    Route::get('password/forgot', 'Auth\AdminForgotPasswordController@showForgotForm')->name('admin.password.forgot');
    Route::post('password/forgot', 'Auth\AdminForgotPasswordController@sendResetLink')->name('admin.password.forgot.post');
    Route::get('password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset/', 'Auth\AdminResetPasswordController@reset')->name('admin.password.reset.post');

    Route::group(['middleware' => ['auth']], function () {
        // - dashboard
        Route::get('', 'Admin\DashboardController@index');
        Route::get('dashboard', 'Admin\DashboardController@index');
    
        // - profile
        Route::get('profile', 'Admin\ProfileController@index');
        Route::post('profile', 'Admin\ProfileController@edit');
    
        // - password
        Route::get('password', 'Admin\PasswordController@index');
        Route::post('password', 'Admin\PasswordController@edit');
    
        // - page
        Route::get('page', 'Admin\PageController@index');
        Route::get('page/add', 'Admin\PageController@addForm');
        Route::post('page/add', 'Admin\PageController@add');
        Route::get('page/edit/{id}', 'Admin\PageController@editForm')->where('id', '[0-9]+');
        Route::post('page/edit/{id}', 'Admin\PageController@edit')->where('id', '[0-9]+');
        Route::post('page/delete', 'Admin\PageController@delete');
        Route::post('page/delete/image', 'Admin\PageController@deleteImage');
    
        // - blog
        Route::get('blog', 'Admin\BlogController@index');
        Route::get('blog/add', 'Admin\BlogController@addForm');
        Route::post('blog/add', 'Admin\BlogController@add');
        Route::get('blog/edit/{id}', 'Admin\BlogController@editForm')->where('id', '[0-9]+');
        Route::post('blog/edit/{id}', 'Admin\BlogController@edit')->where('id', '[0-9]+');
        Route::post('blog/delete', 'Admin\BlogController@delete');
        Route::post('blog/delete/image', 'Admin\BlogController@deleteImage');
    
        // - product
        Route::get('product', 'Admin\ProductController@index');
        Route::post('product/add', 'Admin\ProductController@add');
        Route::get('product/edit/{id}', 'Admin\ProductController@editForm')->where('id', '[0-9]+');
        Route::post('product/edit/{id}', 'Admin\ProductController@edit')->where('id', '[0-9]+');
        Route::post('product/delete', 'Admin\ProductController@delete');
        Route::post('products/delete/all', 'Admin\ProductController@deleteAllProducts');
        Route::get('product/upload/excel', 'Admin\ProductController@uploadExcelForm');
        Route::post('product/upload/excel', 'Admin\ProductController@uploadExcel');
        Route::get('product/import/show', 'Admin\ProductController@importShow');
        Route::get('product/import/process', 'Admin\ProductController@importProcess');
    
        // - product category
        Route::get('product/category', 'Admin\ProductCategoryController@index');
        Route::get('product/category/add', 'Admin\ProductCategoryController@addForm');
        Route::post('product/category/add', 'Admin\ProductCategoryController@add');
        Route::get('product/category/edit/{id}', 'Admin\ProductCategoryController@editForm')->where('id', '[0-9]+');
        Route::post('product/category/edit/{id}', 'Admin\ProductCategoryController@edit')->where('id', '[0-9]+');
        Route::post('product/category/delete', 'Admin\ProductCategoryController@delete');
        Route::post('product/category/delete/image', 'Admin\ProductCategoryController@deleteImage');
    
        // - tracking
        Route::get('tracking', 'Admin\TrackingController@index');
        Route::post('tracking/add', 'Admin\TrackingController@add');
        Route::get('tracking/edit/{id}', 'Admin\TrackingController@editForm')->where('id', '[0-9]+');
        Route::post('tracking/edit/{id}', 'Admin\TrackingController@edit')->where('id', '[0-9]+');
        Route::post('tracking/delete', 'Admin\TrackingController@delete');
        Route::post('tracking/delete/image', 'Admin\TrackingController@deleteImage');
    
        // - banner
        Route::get('banner/{id}', 'Admin\BannerController@index')->where('id', '[0-9]+');
        Route::get('banner/edit/{id}', 'Admin\BannerController@editForm')->where('id', '[0-9]+');
        Route::post('banner/edit/{id}', 'Admin\BannerController@edit')->where('id', '[0-9]+');
        Route::post('banner/delete/image', 'Admin\BannerController@deleteImage');
    
        // - info
        Route::get('info', 'Admin\InfoController@index');
        Route::post('info', 'Admin\InfoController@edit');
        Route::post('info/delete/logo', 'Admin\InfoController@deleteLogo');
    
        // - embed
        Route::get('embed', 'Admin\EmbedController@index');
        Route::post('embed', 'Admin\EmbedController@edit');
    });

});


Route::group(['middleware' => ['web']], function () {

    // - common
    Route::get('', 'HomepageController@index');
    Route::get('/product', 'ProductController@index');
    Route::get('/product/{id}/{slug}', 'ProductController@show')->where('id', '[0-9]+')->where('slug', '[A-Za-z0-9\-]+');
    Route::get('/tracking', 'TrackingController@index');
    Route::get('/blog', 'BlogController@index');
    Route::get('/blog/{slug}', 'BlogController@show');
    Route::get('{slug}', 'PageController@show')->where('slug', '[A-Za-z0-9\-]+');

    // - shopping cart
    Route::get('/cart', 'CartController@index');
    Route::get('/cart/info', 'CartController@info');
    Route::post('/cart/info', 'CartController@infoUpdate');
    Route::get('/cart/confirm', 'CartController@confirm');
    Route::get('/cart/complete/{orderId}/{orderKey}', 'CartController@complete');
    Route::post('/cart/checkout', 'CartController@checkout');
    Route::post('/cart/update', 'CartController@addItemToCart');
    Route::post('/cart/delete', 'CartController@removeItemInCart');
    Route::get('/purchase/{orderId}/{orderKey}', 'OrderController@purchase');

    // - self collection
    Route::get('/selfcollection', 'SelfCollectionController@AvailableTime');
    Route::get('/selfcollection/add/{time}', 'SelfCollectionController@testAddAppointment');

    // - ajax
    Route::post('/address/amphure/{format}', 'AddressController@amphure')->where('format', 'json|xml');
    Route::post('/address/district/{format}', 'AddressController@district')->where('format', 'json|xml');

});
