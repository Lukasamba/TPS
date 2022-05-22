@extends('layouts.navbar')

@section('content')
<link rel="stylesheet" href="{{ asset('/css/listStyle.css') }}">

<div>
    
</div>
<h1>Pasirinktas projektas</h1>
<table class="center">
<thead>
    <tr>
        <th>Projekto pavadinimas</th>
        <th>Projekto aprašymas</th>
        <th>Komandos</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>{{ $results->projectName}}</td>
        <td>{{ $results->projectDescription}}</td>
        <td>
            @foreach ($arr as $team)
                {{ $team->teamName }}
            @endforeach
        </td>
    </tr>
</tbody>
</table>

@endsection