<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function openWelcomePage(){
        return view('welcomepage');
    }

    public function openHomePage(){
        return view('homepage');
    }

    public function openProjectsPage(){
        return view('projectspage');
    }
}