@extends('layouts.navbar')

@section('content')
<div class="jumbotron">
  <h1>LPS - Laiko planavimo sistema</h1>
  <p class="lead">"Leiskite planuoti laiką sau"</p>
  @if(session()->has('userName'))
    <h4>Esate prisijungęs kaip: {{ session()->get('userName') }}</h4>
  @else
    <a href="{{route('signin')}}" class="btn btn-primary btn-large">Click here to sign in</a>
  @endif
</div>
@endsection
