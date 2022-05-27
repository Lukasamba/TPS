@extends('layouts.navbar')

@section('content')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" />
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.6.0/main.css">

<div style="max-width: 1100px;
margin: 40px auto;
padding: 0 10px;">
<a class="btn btn-primary" href=/eventGenerate/single>Rasti laika</a>
<a class="btn btn-primary" href=/eventGenerate/sprint>Generuoti Sprinta</a>
{{-- <a class="btn btn-primary" href=/calendar/newTest>Sukurti Keliems Useriams (Test)</a> --}}
</div>

@endsection
