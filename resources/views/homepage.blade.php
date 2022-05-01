@extends('layouts.navbar')

@section('content')
<div class="jumbotron">
  <h1>TPS - Time Planning System</h1>
  <p class="lead">This is a Time planing Web app that is using Microsoft PHP Graph API</p>
  @if(session()->has('userName'))
    <h4>You are logged in as user: {{ session()->get('userName') }}</h4>
    <p>Use the navigation bar at the top of the page to get started.</p>
  @else
    <a href="{{route('signin')}}" class="btn btn-primary btn-large">Click here to sign in</a>
  @endif
</div>
@endsection
