@extends('layouts.navbar')

@section('content')

<head>
    <link href="{{ asset('css/team.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body>
    <br>
    <div class="main">
        <h1>Komandų sąrašas</h1>
    </div>

    <br>
    <div class="main">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Komandos pavadinimas</th>
                    <th></th>
            </thead>
            @foreach($teams as $team)

            <tbody class="table-group-divider">
                <tr>
                    <td>{{$team->teamId}}</td>
                    <td>{{$team->teamName}}</td>
                    <td>
                        <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                class="material-icons">&#xE417;</i></a>
                        <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i
                                class="material-icons">&#xE254;</i></a>
                        <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i
                                class="material-icons">&#xE872;</i></a>
                    </td>
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>

    <div class="main">
        <a href="/teams/create">Sukurti komandą</a>
    </div>
</body>
@endsection