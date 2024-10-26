<?php

use App\Http\Controllers\Admin\TaskManagementController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth'])->name('dashboards');

Auth::routes();

Route::get('/', function () {
    if (Auth::user()->role === 'admin') {
        return view('welcome');
    }

    return redirect('/home');
})->middleware('auth');

Route::resource('task', TaskManagementController::class, [
    'as' => 'admin',
    'parameters' => [
        'task' => 'id',
    ],
])->middleware('auth');


Route::put('/admin/task/update/{id}', [TaskManagementController::class, 'taskUpdate'])->name('admin.status.update');


// Route::resource('/home', HomeController::class, [
//     'as' => 'admin',
//     'parameters' => [
//         'home' => 'id',
//     ],
// ])->middleware('auth');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::delete('/delete/{id}', [HomeController::class, 'delete'])->name('task.delete')->middleware('auth');
