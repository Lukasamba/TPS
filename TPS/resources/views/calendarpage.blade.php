@extends('layouts.navbar')

@section('content')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" />
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.6.0/main.css">

  <div id='top'>
    Locales:
    <select id='locale-selector'></select>
</div>
<div id='calendar'></div>

<!-- Modal -->



<div id="calendarModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="title"></h5>
          </div>
          <div class="modal-body">
            <p id="monthDayWeekDay"></p>
            <hr>Aprasymas:
            <p id="description"></p>
          </div>
          <div class="modal-footer">
            <p id="location">
          </div>
        </div>
      </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.11.0/main.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/moment@2.27.0/min/moment.min.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.20/moment-timezone.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.20/moment-timezone-with-data.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
  <script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
  <script src="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js"></script>
  <script src=https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js></script>


  <script>
//       var arrays = [{
//   "title": "All Day Event",
//   "start": "2021-04-01 00:00:00",
//   "color": "#40E0D0"
// }, {
//   "title": "Long Event",
//   "start": "2016-01-07 00:00:00",
//   "color": "#FF0000"
// }, {
//   "title": "Repeating Event",
//   "start": "2016-01-09 16:00:00",
//   "color": "#0071c5"
// }, {
//   "title": "Conference",
//   "start": "2016-01-11 00:00:00",
//   "color": "#40E0D0"
// }, {
//   "title": "Meeting",
//   "start": "2016-01-12 10:30:00",
//   "color": "#000"
// }, {
//   "title": "Lunch",
//   "start": "2016-01-12 12:00:00",
//   "color": "#0071c5"
// }, {
//   "title": "Happy Hour",
//   "start": "2016-01-12 17:30:00",
//   "color": "#0071c5"
// }, {
//   "title": "Dinner",
//   "start": "2016-01-12 20:00:00",
//   "color": "#0071c5"
// }, {
//   "title": "Birthday Party",
//   "start": "2016-01-14 07:00:00",
//   "color": "#FFD700"
// }, {
//   "title": "Double click to change",
//   "start": "2016-01-28 00:00:00",
//   "color": "#008000"
// }, {
//   "title": "512",
//   "start": "2021-04-04 00:00:00",
//   "color": "#FF0000"
// }, {
//   "title": "21512",
//   "start": "2021-04-06 00:00:00",
//   "color": "#FF0000"
// }, {
//   "title": "236234",
//   "start": "2021-04-07 00:00:00",
//   "color": "#FF0000"
// }, {
//   "title": "3521",
//   "start": "2021-04-03 00:00:00",
//   "color": "#00FF00"
// }, {
//   "title": "HHH",
//   "start": "2021-04-02 00:00:00",
//   "color": "#FFFF00"
// }]
document.addEventListener('DOMContentLoaded', function() {
    var initialLocaleCode = 'lt';
    var dates = <?php echo json_encode($events); ?>;
  var localeSelectorEl = document.getElementById('locale-selector');
  var now = <?php echo json_encode($now); ?>;
  var calendarEl = document.getElementById('calendar');
  var timezone = <?php echo json_encode($timezone); ?>;
  var curmonth = <?php echo json_encode($currentMonth); ?>;
  var calendar = new FullCalendar.Calendar(calendarEl, {

    buttonIcons: true,
    timeZone: timezone,
    navLinks: true,
    selectable: true,
    selectMirror: true,
    weekNumbers: true,
    initialView: 'dayGridMonth',
    nowIndicator: true,
    initialDate: now,
    now: now,
    dayMaxEvents: true,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth timeGridWeek timeGridDay'
    },
    titleFormat: { // will produce something like "Tuesday, September 18, 2018"
    month: 'long',
    year: 'numeric',
    day: 'numeric',
  },
  eventTimeFormat: { // like '14:30:00'
    hour: '2-digit',
    minute: '2-digit',
    meridiem: false,
    hour12: false
  },
  validRange: {
    start: curmonth
  },
  locale: initialLocaleCode,
  eventDidMount: function(view) {
      //loop through json array
      $(dates).each(function(i, val) {
        //find td->check if the title has same value-> get closest daygird ..change color there
        $("td[data-date=" + moment(val.start).format("YYYY-MM-DD") + "] .fc-event-title:contains(" + val.title + ")").closest(".fc-daygrid-event-harness").css("background-color", val.color);
      })
    },

    eventClick:  function(arg) {
                // endtime = $.fullCalendar.moment(event.end).format('h:mm');
                // var mywhen = starttime + ' - ' + endtime;
                temporary =moment.utc(arg.event.start, 'YYYY-MM-DD HH:mm:ss');
                temporaryEnd =moment.utc(arg.event.end, 'YYYY-MM-DD HH:mm:ss');
                if (temporary.format('MM') == "01"){
                    valMonth = "Sausio";
                }
                else if(temporary.format('MM') == "02"){
                    valMonth = "Vasari";
                }
                else if(temporary.format('MM') == "03"){
                    valMonth = "Kovo";
                }
                else if(temporary.format('MM') == "04"){
                    valMonth = "Balandžio";
                }
                else if(temporary.format('MM') == "05"){
                    valMonth = "Gegužės";
                }
                else if(temporary.format('MM') == "06"){
                    valMonth = "Birželio";
                }
                else if(temporary.format('MM') == "07"){
                    valMonth = "Liepos";
                }
                else if(temporary.format('MM') == "08"){
                    valMonth = "Rugpjūčio";
                }
                else if(temporary.format('MM') == "09"){
                    valMonth = "Rugsėjo";
                }
                else if(temporary.format('MM') == "10"){
                    valMonth = "Spalio";
                }
                else if(temporary.format('MM') == "11"){
                    valMonth = "Lapkričio";
                }
                else if(temporary.format('MM') == "12"){
                    valMonth = "Gruodžio";
                }
                if (temporary.format('d') == "1"){
                    valWeekDay = "Pirmadenis";
                }
                else if(temporary.format('d') == "2"){
                    valWeekDay = "Antradienis";
                }
                else if(temporary.format('d') == "3"){
                    valWeekDay = "Trečiadienis";
                }
                else if(temporary.format('d') == "4"){
                    valWeekDay = "Ketvirtadienis";
                }
                else if(temporary.format('d') == "5"){
                    valWeekDay = "Penktadienis";
                }
                else if(temporary.format('d') == "6"){
                    valWeekDay = "Šeštadienis";
                }
                else if(temporary.format('d') == "0"){
                    valWeekDay = "Sekmadienis";
                }
                timeInterval = temporary.format('kk:mm') + " - " + temporaryEnd.format('kk:mm')
                stringTemps = valMonth + ' ' + temporary.format('D') + ' diena, ' + valWeekDay + ', ' + timeInterval;
                $('#title').html(arg.event.title);
                $('#monthDayWeekDay').text(stringTemps);
                //$('#time').text(timeInterval);
                $('#location').text(arg.event.extendedProps.location);
                $('#description').text(arg.event.extendedProps.description);
                $('#calendarModal').modal();
            },
    editable: true,
    dayMaxEvents: true,
    events: dates
  });

  calendar.render();
  calendar.getAvailableLocaleCodes().forEach(function(localeCode) {
    var optionEl = document.createElement('option');
    optionEl.value = localeCode;
    optionEl.selected = localeCode == initialLocaleCode;
    optionEl.innerText = localeCode;
    localeSelectorEl.appendChild(optionEl);
  });

  localeSelectorEl.addEventListener('change', function() {
    if (this.value) {
      calendar.setOption('locale', this.value);
    }
  });
});
  </script>
@endsection
