<?php

use App\Http\Controllers\DataFileController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SensorDataController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('sites', SiteController::class);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/fft', function () {
    return view('fft-graph');
});

Auth::routes();



Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::resource('/users', UserController::class);
    Route::get('/users/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::get('/users/status/{user}', [UserController::class, 'statusToggle'])->name('users.status');
    Route::resource('/menus', MenuController::class)->except(['show']);
    Route::resource('/roles', RoleController::class)->except(['create', 'show']);
    Route::resource('/factories', FactoryController::class)->except(['show']);
    Route::resource('/sites', SiteController::class);
    Route::resource('/inspections', InspectionController::class);
    Route::resource('/devices', DeviceController::class, ['only' => ['index', 'show']])->names([
        'index' => 'devices.index'
    ]);

    Route::controller(DataFileController::class)
        ->as('data.')
        ->group(function () {
            Route::get('/data', 'index')->name('index');
            Route::get('/data/{data_file}/edit', 'edit')->name('edit');
            Route::put('/data/{data_file}', 'update')->name('update');
            Route::delete('/data/{data_file}', 'destroy')->name('delete');
            Route::get('/data/download/{data_file}', 'download')->name('download');
        });
});


Route::resource('/sensor_data', SensorDataController::class);
Route::post('/sensor_data/generate_plot', [SensorDataController::class, 'generatePlot'])->name('sensor_data.generate_plot');
Route::post('/sensor-data/generate-time-domain-plot', [SensorDataController::class, 'generateTimeDomainPlot'])->name('sensor_data.generate_time_domain_plot');
Route::post('/sensor-data/generate-frequency-domain-plot', [SensorDataController::class, 'generateFrequencyDomainPlot'])->name('sensor_data.generate_frequency_domain_plot');