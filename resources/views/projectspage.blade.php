@extends('layouts.navbar')

@section('content')
<link rel="stylesheet" href="{{ asset('/css/listStyle.css') }}">


<h1 style="font-size: 35px">Mano projektai</h1>
<table class="center"; style="width: 50%">
    <thead>
        <tr>
            <th style="width: 30%">Projekto pavadinimas</th>
        </tr>
    </thead>
    <tbody>
        @forelse($allMyProjects as $project)
        <tr>
            <td> <a href="{{ url('showProject/'.$project->projectId)}}"; 
                style="text-decoration: none; color: black">{{$project->projectName}}</a> </td>
        </tr>
        @empty
        @endforelse
    </tbody>
</table>

<h1 style="font-size: 35px">Visi projektai</h1>
<table class="center"; style="width: 50%">
    <thead>
        <tr>
            <th style="width: 30%">Projekto pavadinimas</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($allprojects as $project)
        <tr>
            <td> <a href="{{ url('showProject/'.$project->projectId)}}"; 
                style="text-decoration: none; color: black">{{$project->projectName}}</a> </td>
        </tr>
        @empty
        @endforelse
    </tbody>
</table>

<div id="container" style="padding-bottom: 50px">
    <a class="button" href='/projects/new'>Sukurti projekta</a>
</div>



</div>

@endsection