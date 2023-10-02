.
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
Route::get('/login', function () {
    if (Auth::guard('loyal_customer')->check() && Auth::guard('loyal_customer')->user()) {
        return redirect('/listUser');
    };
    return view('auth.login');
})->name('auth.login');

Route::post('/login', [UserController::class, 'postLogin'])->name('post_login');
Route::get('/register', function () {
    return view('auth.register');
});
Route::post('/register', [UserController::class, 'postRegister'])->name('post_register');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::middleware(['verfiy-account'])->group(function () {
    Route::get('/listUser', [UserController::class, 'getAllUser', 'search'])->name('get_all_user');
    Route::get('/delete', [UserController::class, 'deleteUser'])->name('delete_user');
    Route::get('/add-user', function () {
        return view('add-user');
    });
    Route::post('/add-user', [UserController::class, 'postAddUser'])->name('post_add_user');
    Route::get('/edit-user/{id}', [UserController::class, 'editUser'])->name('edit_user');
    Route::post('/update-user/{id}', [UserController::class, 'updateUser'])->name('update_user');
});
