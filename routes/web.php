<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PolresController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::get('register/petugas', [AuthController::class, 'register_petugas'])->name('register.petugas');
Route::post('proses_register', [AuthController::class, 'proses_register'])->name('proses_register');
Route::post('proses_register_petugas', [AuthController::class, 'proses_register_petugas'])->name('proses_register_petugas');
Route::post('proses_login', [AuthController::class, 'proses_login'])->name('proses_login');

Route::group(['middleware' => ['auth']], function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('setting', [SettingController::class, 'index'])->name('setting');
    Route::get('setting/getListData', [SettingController::class, 'listData'])->name('setting.list');
    Route::get('setting/add', [SettingController::class, 'create'])->name('setting.add');
    Route::post('setting/store', [SettingController::class, 'store'])->name('setting.store');
    Route::get('setting/edit/{id}', [SettingController::class, 'edit'])->name('setting.edit');
    Route::put('setting/update/{id}', [SettingController::class, 'update'])->name('setting.update');

    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('user/getListData', [UserController::class, 'listData'])->name('user.list');
    Route::get('user/add', [UserController::class, 'create'])->name('user.add');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

    Route::get('polres', [PolresController::class, 'index'])->name('polres');
    Route::get('polres/getListData', [PolresController::class, 'listData'])->name('polres.list');
    Route::get('polres/add', [PolresController::class, 'create'])->name('polres.add');
    Route::post('polres/store', [PolresController::class, 'store'])->name('polres.store');
    Route::get('polres/edit/{id}', [PolresController::class, 'edit'])->name('polres.edit');
    Route::put('polres/update/{id}', [PolresController::class, 'update'])->name('polres.update');
    Route::get('polres/delete/{id}', [PolresController::class, 'destroy'])->name('polres.delete');

    Route::get('jabatan', [JabatanController::class, 'index'])->name('jabatan');
    Route::get('jabatan/getListData', [JabatanController::class, 'listData'])->name('jabatan.list');
    Route::get('jabatan/add', [JabatanController::class, 'create'])->name('jabatan.add');
    Route::post('jabatan/store', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::get('jabatan/edit/{id}', [JabatanController::class, 'edit'])->name('jabatan.edit');
    Route::put('jabatan/update/{id}', [JabatanController::class, 'update'])->name('jabatan.update');
    Route::get('jabatan/delete/{id}', [JabatanController::class, 'destroy'])->name('jabatan.delete');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('profile/getListData', [ProfileController::class, 'listData'])->name('profile.list');
    Route::get('profile/show/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('profile/verifikasi/{id}', [ProfileController::class, 'verifikasi'])->name('profile.verifikasi');
    Route::post('profile/export', [ProfileController::class, 'export'])->name('profile.export');
    Route::get('profile/exportsemua', [ProfileController::class, 'export_semua'])->name('profile.exportsemua');
    Route::put('profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/delete/{id}', [ProfileController::class, 'destroy'])->name('profile.delete');

    Route::get('petugas', [PetugasController::class, 'index'])->name('petugas');
    Route::get('petugas/getListData', [PetugasController::class, 'listData'])->name('petugas.list');
    Route::get('petugas/show/{id}', [PetugasController::class, 'show'])->name('petugas.show');
    Route::get('petugas/edit/{id}', [PetugasController::class, 'edit'])->name('petugas.edit');
    Route::put('petugas/update/{id}', [PetugasController::class, 'update'])->name('petugas.update');
    Route::get('petugas/delete/{id}', [PetugasController::class, 'destroy'])->name('petugas.delete');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    
});
