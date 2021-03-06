<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EventGeneratingController;
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

Route::get('/teams', [TeamController::class, 'index'])->name('TeamsPage')->middleware('IsLogged');
Route::get('/teams/create', [TeamController::class, 'create'])->name('TeamCreatePage')->middleware('IsLogged');
Route::get('/teams/{teamId}/edit', [TeamController::class, 'edit'])->name('TeamEditPage')->middleware('IsLogged');
Route::get('/teams/create/search', [TeamController::class, 'searchTeamCreate'])->name('TeamCreatePageSearch')->middleware('IsLogged');
Route::post('/save-team', [TeamController::class, 'insertTeam'])->name('saveTeam')->middleware('IsLogged');

Route::get('/projects', [ProjectController::class, 'openProjectsPage'])->name('ProjectsPage')->middleware('IsLogged');
Route::get('/calendar', [CalendarController::class, 'openCalendarPage'])->name('CalendarPage')->middleware('IsLogged');

Route::get('/signin', [AuthController::class, 'signin'])->name('signin')->middleware('AlreadyLogged');
Route::get('/callback', [AuthController::class, 'callback'])->name('callback')->middleware('AlreadyLogged');
Route::get('/signout', [AuthController::class, 'signout'])->name('signout')->middleware('IsLogged');

Route::any('/sync', [AuthController::class, 'sync'])->name('sync')->middleware('IsLogged');
Route::get('/eventGenerate', [EventGeneratingController::class, 'openGeneratingPage'])->name('GenerateEvent')->middleware('IsLogged');

Route::get('/eventGenerate/single/{id}', [EventGeneratingController::class, 'getGenerateEventForm'])->name('getGenerateEventForm')->middleware('IsLogged');
Route::post('/eventGenerate/single', [EventGeneratingController::class, 'generateEvent'])->name('generateEvent')->middleware('IsLogged');
Route::get('/eventGenerate/sprint/{id}', [EventGeneratingController::class, 'getGenerateSprintForm'])->name('getGenerateSprintForm')->middleware('IsLogged');
Route::post('/eventGenerate/sprint', [EventGeneratingController::class, 'generateSprint'])->name('generateSprint')->middleware('IsLogged');

Route::get('/calendarDummy', [CalendarController::class, 'calendarDummy'])->name('calendarDummy')->middleware('IsLogged');
Route::get('/calendarDummy/new', [CalendarController::class, 'getNewEventForm'])->name('getNewEventForm')->middleware('IsLogged');
Route::post('/calendarDummy/new', [CalendarController::class, 'createNewEvent'])->name('createNewEvent')->middleware('IsLogged');
Route::get('/calendar/new', [CalendarController::class, 'getNewEventForm'])->name('getNewEventForm')->middleware('IsLogged');
Route::post('/calendar/new', [CalendarController::class, 'createNewEvent'])->name('createNewEvent')->middleware('IsLogged');
Route::get('/calendar/newTest', [CalendarController::class, 'getNewEventFormTest'])->name('getNewEventFormTest')->middleware('IsLogged');
Route::post('/calendar/newTest', [CalendarController::class, 'createNewEventTest'])->name('createNewEventTest')->middleware('IsLogged');
Route::post('/calendar/edit', [CalendarController::class, 'editEventForm'])->name('editEventForm')->middleware('IsLogged');
Route::post('/calendar/editinit', [CalendarController::class, 'initEditEvent'])->name('initEditEvent')->middleware('IsLogged');
Route::post('/calendar/delete', [CalendarController::class, 'deleteEvent'])->name('deleteEvent')->middleware('IsLogged');

Route::get('/projects/new', [ProjectController::class, 'getNewProjectForm'])->name('getNewProjectForm')->middleware('IsLogged');
//Route::post('/projects/new', [ProjectController::class, 'createNewProject'])->name('createNewProject')->middleware('IsLogged');
Route::get('showProject/{id}', [ProjectController::class, 'showProject'])->name('showProject')->middleware('IsLogged');
Route::post('/saveProject', [ProjectController::class, 'insertProject', 'getTeamNames'])->name('insertProject')->middleware('IsLogged');
