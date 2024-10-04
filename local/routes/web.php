<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\Checkjwt;
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

Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/unauthorized', function () {
    // return view('unauthorized');
    echo '403 Forbidden Access!!';
});

Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware(['auth'])->get('/ubahpassword', [LoginController::class, 'ubahpassword']);
Route::middleware(['auth'])->post('/submitpassword', [LoginController::class, 'submitpassword']);

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/masteruser', [AdminController::class, 'masteruser']);
    Route::post('/submituser', [AdminController::class, 'submituser']);
    Route::get('/deleteuser/{id}', [AdminController::class, 'deleteuser']);
    Route::get('/edituser/{id}', [AdminController::class, 'edituser']);
    Route::post('/submitedituser', [AdminController::class, 'submitedituser']);
    Route::post('/resetpass', [AdminController::class, 'resetpass']);

    Route::get('/masterruang', [AdminController::class, 'masterruang']);
    Route::post('/submitruang', [AdminController::class, 'submitruang']);
    Route::get('/deleteruang/{id}', [AdminController::class, 'deleteruang']);
    Route::get('/editruang/{id}', [AdminController::class, 'editruang']);
    Route::post('/updateruang', [AdminController::class, 'updateruang']);
    Route::post('/getruang', [AdminController::class, 'getruang']);
    Route::post('/getgedung', [AdminController::class, 'getgedung']);

    Route::get('/monitorruang', [AdminController::class, 'monitorruang']);
    Route::get('/dalamruang/{id}', [AdminController::class, 'dalamruang']);
    Route::post('/resetkel', [AdminController::class, 'resetkel']);

    Route::post('/aktifkanrole', [AdminController::class, 'aktifkanrole']);
});


Route::middleware(['checkjwt:LOruang'])->prefix('kelas')->group(function () {
    Route::get('/', [PetugasController::class, 'index']);
    Route::post('/', [PetugasController::class, 'index_post']);

    Route::post('/wheel', [PetugasController::class, 'bukakelas']);
    Route::post('/history', [PetugasController::class, 'history']);
    Route::post('/simpandataterpilih', [PetugasController::class, 'simpandataterpilih']);
    
});