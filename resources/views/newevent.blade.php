@extends('layouts.navbar')

@section('content')

<div style="max-width: 1100px;
margin: 40px auto;
padding: 0 10px;">
<h1>Sukurti</h1>
<form method="POST">
  @csrf
  <div class="form-group">
    <label>Ivykio pavadinimas</label>
    <input type="text" class="form-control" name="eventSubject" />
  </div>
  <div class="row">
    <div class="col-sm">
      <div class="form-group">
        <label>Diena</label>
        <input type="date" class="form-control" id="txtDate" id="eventDay"/>
      </div>
      @error('eventDay')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
    </div>
    <div class="col-sm">
      <div class="form-group">
        <label>Pradzia</label>
        <input type="time" class="form-control" name="timeStart" id="timeStart" min="08:00" max="19:00"/>
      </div>
      @error('timeStart')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
    </div>
  <div class="col-sm">
    <div class="form-group">
      <label>Pabaiga</label>
      <input type="time" class="form-control" name="timeEnd" id="timeEnd" min="08:00" max="19:00"/>
    </div>
    @error('timeEnd')
      <div class="alert alert-danger">{{ $message }}</div>
    @enderror
  </div>

</div>

  <div class="form-group">
    <label>Aprasas</label>
    <textarea type="text" class="form-control" name="eventBody" rows="3"></textarea>
  </div>
  <div >
  </div>
  <div class="form-group">
    <label>Tipas</label>
    <select class="custom-select" id="inputGroupSelect03">
      <option selected>Choose...</option>
      <option value="1">Sprint Review</option>
      <option value="2">Retrospektyva</option>
      <option value="3">Stand-up</option>
      <option value="3">Sprint planning</option>
    </select>
  </div>
  <input type="submit" class="btn btn-primary mr-2" value="Create" />
  <a class="btn btn-secondary" href=/calendar>Atsaukti</a>
</form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
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
});
    </script>
@endsection
