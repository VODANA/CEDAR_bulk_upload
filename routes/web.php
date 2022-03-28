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
  Route::get('/allegrosyncs/sync', function () {
    return redirect(route('allegrosyncs.sync'));
  });

  Route::get('/dhissync/sync', [DHISSyncController::class, 'syncForm']);
  Route::post('/dhissync/sync', [DHISSyncController::class, 'syncDHIS'])->name('syncdhis');

  
  
  
  //Route::get('/backups/restore', fDHISSyncControllerunction () {
    //return "WTF";
  //});
 // Route::post('/backups/restore', 'BackupController@restore')->name('restore'); 
//  Route::post('backups/restore', array('as'=>'BackupController.restore', 'uses'=>'BackupController@restore'));

  //Route::get('/allegrosync/sync', [AllegroSync::class, 'allegrosync/sync']);
  //Route::post('/post', [AllegroSync::class, 'allegroSync'])->name('allegroSync');
  //Route::get('/backups/restore', [BackupController::class, 'restoreDb']);
  //Route::post('/backups/restore', [BackupController::class, 'restore'])->name('restore');


  //Route::resource('products', 'ProductController'); 
  Route::resource('templates', 'TemplateController'); 
  Route::resource('bulkuploads', 'BulkUploadController'); 
  Route::resource('allegrosyncs', 'AllegroSyncController'); 
  Route::resource('settings', 'SettingController'); 
  Route::resource('permissions', 'PermissionController'); 
  Route::resource('roles', 'RoleController'); 
  Route::resource('users', 'UserController'); 
  Route::resource('synctoallegros', 'SyncToAllegroController'); 
  Route::resource('backups', 'BackupController'); 
  Route::resource('allegrosettings', 'AllegroSettingController'); 
  Route::resource('dhissyncs', 'DHISSyncController'); 

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

Route::group([ 'namespace'=> '\App\Http\Controllers', 'prefix' => '',  'as'=>'', 'middleware' => 'auth' ], function () { 
  Route::resource('allegrosettings', 'AllegroSettingController'); 
});

Route::group([ 'namespace'=> '\App\Http\Controllers', 'prefix' => '',  'as'=>'', 'middleware' => 'auth' ], function () { 
  Route::resource('allegrosyncs', 'AllegroSyncController'); 
});

Route::group([ 'namespace'=> '\App\Http\Controllers'], function () { 
  Route::resource('synctoallegros', 'SyncToAllegroController'); 
});

Route::group([ 'namespace'=> '\App\Http\Controllers'], function () { 
  Route::resource('backups', 'BackupController'); 
}); 

Route::group([ 'namespace'=> '\App\Http\Controllers'], function () { 
  Route::resource('dhissyncs', 'DHISSyncController'); 
}); 
//Route::get('backups/download-zip', [BackupController::class, 'downloadZip']);
//Route::get('backups/restoreDB', [BackupController::class, 'backups']);
