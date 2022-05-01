<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        return view('homepage');
    }

    public function openTeamsPage(){
        return view('teamspage');
    }

    public function openProjectsPage(){
        return view('projectspage');
    }

    public function openCalendarPage(){
        return view('calendarpage');
    }
}
