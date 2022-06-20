@extends('layouts.navbar')

@section('content')

{{-- Klaidų metimui --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<body>
    <div style="max-width: 1100px;
    margin: 40px auto;
    padding: 0 10px;">
        <h1>Projekto kūrimas</h1>
        <form action="{{ url('saveProject') }}" method="POST">
            @csrf
            <div class="form-group">
                <br>
                <label>Projekto pavadinimas:</label>
                <div class="row">
                    <div class="col-sm">
                        <input type="text" class="form-control" name="projectName" placeholder="Įrašykite projekto pavadinimą" required oninvalid="this.setCustomValidity('Prašome įvesti pavadinimą :)')" oninput="setCustomValidity('')">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <br>
                <label>Aprašymas:</label>
                <div class="row">
                    <div class="col-sm">
                        <textarea type="text" class="form-control" name="projectDescription" rows="3" placeholder="Įrašykite projekto aprašymą" required oninvalid="this.setCustomValidity('Prašome įvesti aprašymą :)')" oninput="setCustomValidity('')"></textarea>
                    </div>
                </div>
            </div>
            <!-- <div class="form-group">
                <span class="details">Pradžia</span>
                <input type="date" class="form-control" id="txtDate" name="startDate" />
            </div>
            <div class="form-group">
                <span class="details">Pabaiga</span>
                <input type="date" class="form-control" id="txtDate" name="endDate" />
            </div> -->
            <div class="form-group" style="padding-top: 10px;">
                <span class="details">Sprintu kiekis</span>
                <input type="number" id="quantity" name="sprintQuantity" min="1" max="10" placeholder="10">
                <span class="details">Sprintu ilgis</span>
                <input type="number" id="quantity" name="sprintLength" min="1" max="7" placeholder="7">
            </div>

            <div class="form-group" style="padding-top: 10px;">
                <span class="details">Projekto komandos</span>
                <select name="teamId" id="">
                    @foreach ($teams as $team)
                    <option value="{{ $team->teamId }}">{{ $team->teamName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="buttons" style="padding-top: 10px;">
                <button type="submit" class="btn btn-primary">Sukurti</button>
                <a class="btn btn-secondary" href=/projects>Atšaukti</a>
            </div>
    </div>

    </form>
    </div>
</body>

<!-- 
{{-- <div class="container">
    <h1>Sukurti projekta</h1>
    <form action="{{ url('saveProject') }}" method="POST">
        @csrf
        <div class="form-group" style="width: 30%">
            <label>Projekto pavadinimas</label>
            <input type="text" class="form-control" name="projectName">
        </div>
        <div class="form-group" style="width: 30%">
            <label>Projekto aprašymas</label>
            <textarea type="text" class="form-control" name="projectDescription" rows="3"></textarea>
        </div>
        <div class="form-group" style="width: 30%">
            <label>Pradžia</label>
            <input type="date" class="form-control" id="txtDate" name="startDate"/>
        </div>
        <div class="form-group" style="width: 30%">
            <label>Pabaiga</label>
            <input type="date" class="form-control" id="txtDate" name="endDate"/>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label>Sprintų kiekis</label>
                    <input type="number" id="quantity" name="sprintQuantity" min="1" max="10">
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label>Sprintų ilgis</label>
                    <input type="number" id="quantity" name="sprintLength" min="1" max="7">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Projekto grupės</label>
            <select name="teamId" id="">
                @foreach ($teams as $team)
                    <option value="{{ $team->teamId }}">{{ $team->teamName }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Sukurti</button>
        <a class="btn btn-secondary" href=/projects>Atsaukti</a>
    </form>
</div> --}} -->

@endsection