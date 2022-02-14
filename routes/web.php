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
    return view('auth.login');
});

Auth::routes();

//Backend : Admin Panel
Route::group([ 'namespace'=> '\App\Http\Controllers\Admin', 'prefix' => 'admin',  'as'=>'admin.', 'middleware' => 'auth' ], function () { 

  Route::get('/', function () {
    return redirect(route('templates.index'));
  });
  
  //naming convention ignored for dashboard as /admin/dashboards sounds inappropriate!
  Route::get('/dashboard', 'DashboardController@index')->name('dashboard'); 
  
  //Route::resource('products', 'ProductController'); 
  Route::resource('templates', 'TemplateController'); 
  Route::resource('bulkuploads', 'BulkUploadController'); 
  Route::resource('settings', 'SettingController'); 
  Route::resource('permissions', 'PermissionController'); 
  Route::resource('roles', 'RoleController'); 
  Route::resource('users', 'UserController'); 

});

//Frontend
/*Route::group([ 'namespace'=> '\App\Http\Controllers', 'prefix' => '',  'as'=>'', 'middleware' => 'auth' ], function () { 
  Route::resource('products', 'ProductController'); 
});
*/
//Frontend
Route::group([ 'namespace'=> '\App\Http\Controllers', 'prefix' => '',  'as'=>'', 'middleware' => 'auth' ], function () { 
  Route::resource('templates', 'TemplateController'); 
});

Route::group([ 'namespace'=> '\App\Http\Controllers', 'prefix' => '',  'as'=>'', 'middleware' => 'auth' ], function () { 
  Route::resource('bulkuploads', 'BulkUploadController'); 
});

Route::group([ 'namespace'=> '\App\Http\Controllers', 'prefix' => '',  'as'=>'', 'middleware' => 'auth' ], function () { 
  Route::resource('settings', 'SettingController'); 
});