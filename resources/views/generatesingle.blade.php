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

<div style="max-width: 1100px;
margin: 40px auto;
padding: 0 10px;">
<h1>Rasti laiką</h1>
<form name="myForm" method="POST" action="{{route('generateEvent')}}" onsubmit="return validateForm()" required>
  @csrf
  <div class="form-group">
    <br>

    <label>Trukmė:</label>
    <div class="row">
        <div class="col-sm">
            <label>Valandos</label>
            <input type="number" class="form-control" name="hours" value="1" max="11" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti valandas :)')" oninput="setCustomValidity('')"/>
            <input type="number" class="form-control" name="idas" value="{{$idss}}" hidden/>
        </div>
        <div class="col-sm">
            <label>Minutės</label>
            <input type="number" class="form-control" name="minutes" value="30" max="59" min="0" required oninvalid="this.setCustomValidity('Prašome įvesti minutes :)')" oninput="setCustomValidity('')"/>
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
    <br>

    <label title="Nurodo po kiek minuciu rezervo iki ivykio bus palikta nuo paskutinio ivykio iki sio ir kiek nuo sio ivykio pabaigos iki kito ivykio">Rezervas:</label>
    <div class="row">
        <div class="col-sm">
            <label>Minutės</label>
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
  <br>
  <label title="Nurodo nuo kokios dienos bei laiko ieskoti anksciausio laisvo laiko">Pradžia:</label>
  <div class="row">
    <div class="col-sm">
      <div class="form-group">

        <label>Diena</label>
        <input type="date" class="form-control"  name="eventDay" value="{{$nowdatee}}" min="{{$nowdatee}}" required oninvalid="this.setCustomValidity('Prašome įvesti dieną :)')" oninput="setCustomValidity('')"/>
      </div>
      @error('eventDay')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
    </div>
    <div class="col-sm">
      <div class="form-group">
        <label>Pradžia</label>
        <input type="time" class="form-control" name="timeStart" id="time" value="{{$nowtime}}" required oninvalid="this.setCustomValidity('Prašome įvesti laiką :)')" oninput="setCustomValidity('')"/>
      </div>
      @error('timeStart')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
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
<div class="row">
    <div class="col-sm">
      <div class="form-group">

        <label>Paieškos dienų intervalas</label>
        <input type="number" class="form-control" name="interval" min="1" value = "1" required oninvalid="this.setCustomValidity('Prašome įvesti intervalą :)')" oninput="setCustomValidity('')"/>
      </div>
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
    <br>

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
            alert("Trukme negali buti mazesne negu 10 minuciu, dabar pasirinkta: " + xy + "minutes");
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
