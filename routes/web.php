<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProjectController;

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

Route::get('/', [MainController::class, 'openWelcomePage'])->name('welcomePage')->middleware('AlreadyLogged');
Route::get('/home', [MainController::class, 'openHomePage'])->name('HomePage')->middleware('IsLogged');

Route::get('/teams', [MainController::class, 'openTeamsPage'])->name('TeamsPage')->middleware('IsLogged');
Route::get('/projects', [ProjectController::class, 'openProjectsPage'])->name('ProjectsPage')->middleware('IsLogged');
Route::get('/calendar', [CalendarController::class, 'openCalendarPage'])->name('CalendarPage')->middleware('IsLogged');

Route::get('/signin', [AuthController::class, 'signin'])->name('signin')->middleware('AlreadyLogged');
Route::get('/callback', [AuthController::class, 'callback'])->name('callback')->middleware('AlreadyLogged');
Route::get('/signout', [AuthController::class, 'signout'])->name('signout')->middleware('IsLogged');