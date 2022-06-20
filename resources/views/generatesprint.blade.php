@extends('layouts.navbar')

@section('content')

<div style="max-width: 1100px;
margin: 40px auto;
padding: 0 10px;">
<h1>Įvykio generavimas</h1>
<form name="myForm" method="POST" action="{{route('generateSprint')}}" onsubmit="return validateForm()" required>
  @csrf
  <div class="form-group">
    <br>
    <div class="row">
    <div class="col-sm">
        <label>Stand-up:</label>
    </div>
    <div class="col-sm">
        <label>SPRINT planavimas:</label>
    </div>
    <div class="col-sm">
        <label>Retrospektyva:</label>
    </div>
    <div class="col-sm">
        <label>SPRINT Apžvalga:</label>
    </div>
    <div class="col-sm">
    </div>
    <div class="col-sm">
    </div>
    <label>Trukmė:</label>
    <div>
    <label>Valandos</label>
    <div class="row">
        <div class="col-sm">
            <input type="number" class="form-control" name="sprintSUHours" value="0" max="11" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti valandas :)')" oninput="setCustomValidity('')"/>
            <input type="number" class="form-control" name="idas" value="{{$idss}}" hidden/>
        </div>
        <div class="col-sm">
            <input type="number" class="form-control" name="sprintSPHours" value="2" max="11" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti valandas :)')" oninput="setCustomValidity('')"/>
        </div>
        <div class="col-sm">
            <input type="number" class="form-control" name="sprintRHours" value="1" max="11" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti valandas :)')" oninput="setCustomValidity('')"/>
        </div>
        <div class="col-sm">
            <input type="number" class="form-control" name="sprintSAHours" value="2" max="11" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti valandas :)')" oninput="setCustomValidity('')"/>
        </div>
        <div class="col-sm">
        </div>
        <div class="col-sm">
        </div>
    </div>
    <label>Minutės</label>
    <div class="row">
        <div class="col-sm">
            <input type="number" class="form-control" name="sprintSUMinutes" value="15" max="59" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti minutes :)')" oninput="setCustomValidity('')"/>
        </div>
        <div class="col-sm">
            <input type="number" class="form-control" name="sprintSPMinutes" value="0" max="59" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti minutes :)')" oninput="setCustomValidity('')"/>
        </div>
        <div class="col-sm">
            <input type="number" class="form-control" name="sprintRMinutes" value="30" max="59" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti minutes :)')" oninput="setCustomValidity('')"/>

        </div>
        <div class="col-sm">
            <input type="number" class="form-control" name="sprintSAMinutes" value="0" max="59" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti minutes :)')" oninput="setCustomValidity('')"/>

        </div>
        <div class="col-sm">
        </div>
        <div class="col-sm">
        </div>
    </div>
    <label title="Nurodo po kiek minuciu rezervo iki ivykio bus palikta nuo paskutinio ivykio iki sio ir kiek nuo sio ivykio pabaigos iki kito ivykio">Rezervas:</label>
<div>
    <label>Minutės</label>
    <div class="row">
        <div class="col-sm">
            <input type="number" class="form-control" name="rezerve" value="10" max="60" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti minutes :)')" oninput="setCustomValidity('')"/>
        </div>
        <div class="col-sm">
        </div>
        <div class="col-sm">
        </div>
        <div class="col-sm">
        </div>
        <div class="col-sm">
        </div>
        <div class="col-sm">
        </div>
    </div>
</div>
<div>


</div>

<br>





  <input type="submit" class="btn btn-primary mr-2" value="Generuoti" />
  <a class="btn btn-secondary" href=/projects>Atšaukti</a>
</form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>

    function validateForm(){

        var x = parseInt(document.forms["myForm"]["hours"].value);
        var y = parseInt(document.forms["myForm"]["minutes"].value);
        var xy = x + y;
        if (xy < 10){
            alert("Trukmė negali būti mažesnė negu 10 minučių, dabar pasirinkta: " + xy + "minutės");
            return false;
        }

    }
    $(function(){
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;

    // or instead:
    // var maxDate = dtToday.toISOString().substr(0, 10);

    $('#txtDate').attr('min', maxDate);
    $('#txtDate').attr('value', maxDate);
});
    </script>
@endsection
