<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::get('/login', function () {
    return  Redirect::route('admin/login');
});
Route::get('/', function () {
    return redirect('/admin/dashboard');
});
Route::group(['middleware' => 'web'], function () {
     //   Route::get('payPremium', ['as'=>'payPremium','uses'=>'PaypalController@payPremium']);
        Route::post('getCheckout', ['as'=>'getCheckout','uses'=>'PaypalController@getCheckout']);
        Route::get('getDone', ['as'=>'getDone','uses'=>'PaypalController@getDone']);
        Route::get('getCancel', ['as'=>'getCancel','uses'=>'PaypalController@getCancel']);
    // Route::auth();
});

// admin/test
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/admin/login','AdminAuth\AuthController@showLoginForm');
    Route::post('/admin/login','AdminAuth\AuthController@login');
    Route::get('/admin/logout','AdminAuth\AuthController@logout');

    // Registration Routes...
    Route::get('admin/register', 'AdminAuth\AuthController@showRegistrationForm');
    Route::post('admin/register', 'AdminAuth\AuthController@register');
    Route::get('/admin', 'AdminController@index');


    Route::get('dashboard', 'Admin\HomeController@index');
    Route::get('user/create', 'Admin\UserController@create');
    Route::post('user/store', 'Admin\UserController@store');
    Route::get('user/show', 'Admin\UserController@show');
    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');
});
$router->group(['prefix' => 'admin/product', 'middleware' => 'auth'], function ($router) {
    $router->get('create', 'Admin\ProductController@create');
    $router->post('store', 'Admin\ProductController@store');
    $router->get('show', 'Admin\ProductController@show');
    $router->get('edit/{id}', 'Admin\ProductController@edit');
    $router->patch('update/{id}', [
        'as' => 'product.update',
        'uses' => 'Admin\ProductController@update'
    ]);
    $router->get('/destroy/{id}','Admin\ProductController@destroy');
});
$router->group(['prefix' => 'admin/category', 'middleware' => 'auth'], function ($router) {
    $router->get('create', 'Admin\CategoryController@create');
    $router->post('store', 'Admin\CategoryController@store');
    $router->get('show', 'Admin\CategoryController@show');
    $router->get('edit/{id}', 'Admin\CategoryController@edit');
    $router->get('destroy/{id}','Admin\CategoryController@destroy');
    $router->patch('update/{id}', [
        'as' => 'category.update',
        'uses' => 'Admin\CategoryController@update'
    ]);
});
$router->group(['prefix' => 'admin'], function () use ($router) {
    $router->get('login', 'Auth\AdminAuth\AuthController@getLogin')->name('get-admin-login');
    $router->post('login', 'Auth\AdminAuth\AuthController@postLogin')->name('post-admin-login');
    $router->get('logout', function () {
        Auth::logout();
        session()->flush();
        Session::flash('success', 'successfull logout ');
        return redirect('/admin/login');
    });
});
Route::get('/login', function () {
    return redirect('admin/login');
});
Route::any('payment/store-payment','PayPalController@getCheckout');
Route::get('/{slug}', function ($slug) {
    if (\App\Model\Product::where('sku', $slug)->count()) {
        $app=app();
        $controller=$app->make('App\Http\Controllers\ProductController');
        return $controller->CallAction('index',[$slug]);
    }  else {
        return view('errors.404');
    }
});
Route::get('register', 'RegistrationController@show')->name('registration');
Route::post('register', 'RegistrationController@store')->name('register');
Route::get('/login', function () {
    return redirect('admin/login');
});