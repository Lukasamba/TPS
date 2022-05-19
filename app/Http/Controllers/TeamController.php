<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'teamName' => 'required',
    //     ]);
    //     $team = new Team;
    //     $team->teamName = $request->teamName;
    //     $team->save();
    //     return redirect()->route('teams.index')
    //         ->with('success', 'Komanda buvo sukurta sėkmingai.');
    // }
    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Team  $team
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Team $company)
    // {
    //     return view('Teams.show', compact('team'));
    // }
    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Team  $Team
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Team $team)
    // {
    //     return view('Teams.edit', compact('team'));
    // }
    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Team  $team
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'teamName' => 'required',
    //     ]);
    //     $team = Team::find($id);
    //     $team->teamName = $request->teamName;
    //     $team->save();
    //     return redirect()->route('teams.index')
    //         ->with('success', 'Komanda buvo atnaujinta sėkmingai');
    // }
    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Team  $company
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Team $team)
    // {
    //     $team->delete();
    //     return redirect()->route('teams.index')
    //         ->with('success', 'Komanda buvo ištrinta sėkminigai');
    // }
}