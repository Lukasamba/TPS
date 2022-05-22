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

Route::get('/', [MainController::class, 'openWelcomePage'])->name('welcomePage')->middleware('AlreadyLogged');
Route::get('/home', [MainController::class, 'openHomePage'])->name('HomePage')->middleware('IsLogged');

Route::get('/teams', [MainController::class, 'openTeamsPage'])->name('TeamsPage')->middleware('IsLogged');
Route::get('/projects', [MainController::class, 'openProjectsPage'])->name('ProjectsPage')->middleware('IsLogged');
Route::get('/calendar', [CalendarController::class, 'openCalendarPage'])->name('CalendarPage')->middleware('IsLogged');

Route::get('/signin', [AuthController::class, 'signin'])->name('signin')->middleware('AlreadyLogged');
Route::get('/callback', [AuthController::class, 'callback'])->name('callback')->middleware('AlreadyLogged');
Route::get('/signout', [AuthController::class, 'signout'])->name('signout')->middleware('IsLogged');

Route::any('/sync', [AuthController::class, 'sync'])->name('sync')->middleware('IsLogged');

Route::get('/calendarDummy', [CalendarController::class, 'calendarDummy'])->name('calendarDummy')->middleware('IsLogged');
Route::get('/calendar/new', [CalendarController::class, 'getNewEventForm'])->name('getNewEventForm')->middleware('IsLogged');
Route::post('/calendar/new', [CalendarController::class, 'createNewEvent'])->name('createNewEvent')->middleware('IsLogged');
Route::get('/calendar/newTest', [CalendarController::class, 'getNewEventFormTest'])->name('getNewEventFormTest')->middleware('IsLogged');
Route::post('/calendar/newTest', [CalendarController::class, 'createNewEventTest'])->name('createNewEventTest')->middleware('IsLogged');
Route::post('/calendar/edit', [CalendarController::class, 'editEventForm'])->name('editEventForm')->middleware('IsLogged');
Route::post('/calendar/editinit', [CalendarController::class, 'initEditEvent'])->name('initEditEvent')->middleware('IsLogged');
Route::post('/calendar/delete', [CalendarController::class, 'deleteEvent'])->name('deleteEvent')->middleware('IsLogged');

Route::get('/projects/new', [ProjectController::class, 'getNewProjectForm'])->name('getNewProjectForm')->middleware('IsLogged');
//Route::post('/projects/new', [ProjectController::class, 'createNewProject'])->name('createNewProject')->middleware('IsLogged');
Route::get('showProject/{id}',[ProjectController::class, 'showProject'])->name('showProject')->middleware('IsLogged');
Route::post('/saveProject',[ProjectController::class, 'insertProject', 'getTeamNames'])->name('insertProject')->middleware('IsLogged');