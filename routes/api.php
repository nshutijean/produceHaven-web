<?php

use Illuminate\Http\Request;

Route::post('login', 'UserController@login');
// Route::post('address', 'AddressController@store', function() {
//     // return Address::with('User')->get();
// });
Route::post('register-buyer', 'UserController@registerBuyer', function() {
    set_time_limit(0);
});
Route::post('register-vendor', 'UserController@registerVendor');
Route::get('/products', 'ProductController@index');
Route::post('/upload-image', 'ProductController@uploadImage');
Route::post('/upload-pdf', 'ProductController@uploadPdf');

Route::get('products/{product}', 'ProductController@show');

Route::get('/all-users', 'UserController@allUsers');
Route::get('/all-orders', 'OrderController@allOrders');
// Route::get('/all-products', 'ProductController@paginatedProducts');

Route::get('/buyers', 'UserController@countBuyers');
Route::get('/vendors', 'UserController@countVendors');
Route::get('/fruits', 'ProductController@countFruits');
Route::get('/vegetables', 'ProductController@countVegetables');
Route::get('/delivers', 'OrderController@countDelivered');
Route::get('/pendings', 'OrderController@pendingOrders');

Route::get('search-product', 'ProductController@searchProduct');



 
Route::group(['middleware' => 'auth:api'], function () {
    //user
    Route::get('logout', 'UserController@logout');
    Route::get('/users', 'UserController@index');
    Route::get('users/{user}', 'UserController@show');
    Route::patch('users/{user}', 'UserController@updateUser'); 
    Route::get('users/{user}/orders', 'UserController@showOrders'); 
    
    //orders
    Route::patch('orders/{order}/deliver', 'OrderController@deliverOrder');
    Route::resource('/orders', 'OrderController');

    //products
    Route::resource('/products', 'ProductController')->except(['index','show']);
    Route::patch('products/{product}/units/add', 'ProductController@updateUnits');
    Route::get('showWithAuth', 'ProductController@showWithAuth');
    // Route::resource('/address', 'AddressController')->except(['index', 'show']);
    // Route::get('orders-count', 'OrderController@count');

});

//Admin
Route::post('login-admin', 'AdministratorController@loginAdmin');
Route::post('register-admin', 'AdministratorController@registerAdmin');

//Email
// Send reset password mail
Route::post('reset-password', 'UserController@sendPasswordResetLink');

// handle reset password form process
Route::post('reset/password', 'UserController@callResetPassword');
