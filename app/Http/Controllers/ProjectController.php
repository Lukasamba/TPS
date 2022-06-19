<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\MessageBag;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Models\TeamProject;
use Auth;


// TEMP
class GroupDemo
{
    public $name;
    public $surname;
    public $id;
}

class ProjectController extends Controller
{
    // Lists all and user specific projects
    public function openProjectsPage()
    {
        static $userId;
        static $myprojects = array();
        static $projectIds = new Collection();
        static $allMyProjects = array();

        // Returns all projects
        $allprojects = Project::all()->sortBy('projectName'); 

        // Get users id
        $userId = User::select(['userId'])->where('userEmail', session()->get('userEmail'))->exists();

        // Get all teams with this users id
        $myTeams = DB::table('team_members')->where('fk_userId', $userId)->get();

        // Get all project IDs from those teams
        foreach ($myTeams as $team) {
            $projectId = DB::table('team_projects')->where('fk_teamId', $team->fk_teamId)->get('fk_projectId');
            if (!is_null($projectId)) {
                $projectIds = $projectIds->merge($projectId);
            }
        }

        // Make project array
        foreach ($projectIds as $pids) {
            $myprojects = DB::table('projects')->where('projectId', $pids->fk_projectId)->get()->first();
            if (!is_null($myprojects)) {
                array_push($allMyProjects, $myprojects);
            }
        }
        $allMyProjects = collect($allMyProjects)->sortBy('projectName')->toArray();
        $teams = DB::table('teams')->get();
        $team_projects = DB::table('team_projects')->get();
        return view('projectspage', compact('allprojects', 'allMyProjects', 'teams', 'team_projects'));
    }

    public function getNewProjectForm()
    {
        $teams = Team::all();
        return view('newProject', compact('teams'));
    }

    // public function createNewProject()
    // {
    //   //$viewData = $this->loadViewData();
    //   //return view('newevent');
    // }

    public function showProject($projectId)
    {
        // Return project by id
        $arr = array();
        $results = DB::table('projects')->where('projectId', $projectId)->get()->first();
        $teamsids = DB::table('team_projects')->where('fk_projectId', $projectId)->get();
        foreach ($teamsids as $team) {
            $teams = DB::table('teams')->where('teamId', $team->fk_teamId)->get()->first();
            array_push($arr, $teams);
        }
        return view('showProject', compact('results'), compact('arr'));
    }

    // Inserts project into database
    public function insertProject(Request $request)
    {
        $validated = $request->validate([
            'projectName' => 'required|max:30',
            'projectDescription' => 'required|max:255'
        ]);

        $project = new Project;
        $project->projectName = $request->input('projectName');
        $project->projectDescription = $request->input('projectDescription');
        $projectId = DB::table('projects')->insertGetId([
            'projectName' => $project->projectName,
            'projectDescription' => $project->projectDescription
        ]);

        // TeamId, ProjectID
        $team_projects = new TeamProject;
        $team_projects->fk_teamId = $request->input('teamId');
        $team_projects->fk_projectId = $projectId;
        $team_projects->save();

        return redirect('projects');
    }
    public function insertTeamProject()
    {
    }
}