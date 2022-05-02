<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;

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

Route::get('/', [MainController::class, 'index'])->name('HomePage');
Route::get('/teams', [MainController::class, 'openTeamsPage'])->name('TeamsPage');
Route::get('/projects', [MainController::class, 'openProjectsPage'])->name('ProjectsPage');
Route::get('/calendar', [CalendarController::class, 'openCalendarPage'])->name('CalendarPage');

Route::get('/signin', [AuthController::class, 'signin'])->name('signin');
Route::get('/callback', [AuthController::class, 'callback'])->name('callback');
Route::get('/signout', [AuthController::class, 'signout'])->name('signout');

Route::any('/sync', [AuthController::class, 'sync'])->name('sync');

Route::get('/calendarDummy', [CalendarController::class, 'calendarDummy'])->name('calendarDummy');
Route::get('/calendarDummy/new', [CalendarController::class, 'getNewEventForm'])->name('getNewEventForm');
Route::post('/calendarDummy/new', [CalendarController::class, 'createNewEvent'])->name('createNewEvent');
