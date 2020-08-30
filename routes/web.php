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

Route::middleware('auth')->group(function () {
    Route::get('/artifacts', 'PrController@getArtifacts')->name('artifacts');
    Route::get('refresh/artifacts', 'PrController@refreshArtifacts')->name('refresh.artifacts');
    Route::get('/deploy/pr/{id}', 'DeployController@deploy')->name('deploy.pr');
    Route::get('/undeploy/pr/{pr}/{id}', 'UnDeployController@undeploy')->name('undeploy.pr');
});

Auth::routes();

