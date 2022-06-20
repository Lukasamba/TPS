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

    <label>Trukme:</label>
    <div class="row">
        <div class="col-sm">
            <label>Valandos</label>
            <input type="number" class="form-control" name="hours" value="1" max="11" min="0" required/>
            <input type="number" class="form-control" name="idas" value="{{$idss}}" hidden/>
        </div>
        <div class="col-sm">
            <label>Minutes</label>
            <input type="number" class="form-control" name="minutes" value="30" max="59" min="0" required/>
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
            <label>Minutes</label>
            <input type="number" class="form-control" name="rezerve" value="10" max="60" min="0" required/>
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
  <label title="Nurodo nuo kokios dienos bei laiko ieskoti anksciausio laisvo laiko">Pradzia:</label>
  <div class="row">
    <div class="col-sm">
      <div class="form-group">

        <label>Diena</label>
        <input type="date" class="form-control"  name="eventDay" value="{{$nowdatee}}" min="{{$nowdatee}}" required/>
      </div>
      @error('eventDay')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
    </div>
    <div class="col-sm">
      <div class="form-group">
        <label>Pradzia</label>
        <input type="time" class="form-control" name="timeStart" id="time" value="{{$nowtime}}" required/>
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

        <label>Paieskos dienu intervalas</label>
        <input type="number" class="form-control" name="interval" min="1" value = "1" required/>
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

    <div class="form-group">
        <label>Ivykio pavadinimas</label>
        <input type="text" class="form-control" name="eventSubject" />
      </div>
      <br>
      <div class="form-group">
    <label>Aprasas</label>
    <textarea type="text" class="form-control" name="eventBody" rows="3"></textarea>
  </div>
  <br>
  <input type="submit" class="btn btn-primary mr-2" value="Generuoti" />
  <a class="btn btn-secondary" href=/eventGenerate>Atsaukti</a>
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
