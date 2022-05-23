<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class TeamController extends Controller
{
    public function index()
    {
        // $data = Team::all();
        $teams = DB::table('teams')->get();
        return view('teamspage', ['teams' => $teams]);
    }

    public function create()
    {
        $userNames = DB::table('users')->pluck('userName');
        $userEmails = DB::table('users')->pluck('userEmail');
        return view('teamcreatepage', ['names' => $userNames], ['emails' => $userEmails]);
    }

    public function edit(Team $team, User $user)
    {
        $userNames = DB::table('users')->pluck('userName');
        $userEmails = DB::table('users')->pluck('userEmail');
        return view('teameditpage',  ['names' => $userNames], ['team' => $team], ['user' => $user], ['emails' => $userEmails]);
    }

    public function searchTeamCreate()
    {
        $search_text = $_GET['query'];
        //$members = Member
    }

    public function insertTeam(Request $request)
    {
        $team = new Team;
        $team->teamName = $request->input('teamName');
        $teamId = DB::table('teams')->insertGetId([
            'teamName' => $team->teamName,
        ]);

        // Nedekit kablelio susimyldami, gale jeigu neirasysit daugiau vardu
        $users = $request->input('query');
        $users = explode(", ", $users);
        foreach ($users as $user) {
            $dbuser = DB::table('users')->where('userName', $user)->first()->userId;

            DB::table('team_members')->insert([
                'fk_teamId' => $teamId,
                'fk_userId' => $dbuser
            ]);
        }

        return redirect('teams');
    }
}