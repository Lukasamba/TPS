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
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">

          </div>
        </div>
      </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.11.0/main.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.11.0/main.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
  <script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
  <script src="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js"></script>
  <script src=https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js></script>


  <script>
      var arrays = [{
  "title": "All Day Event",
  "start": "2021-04-01 00:00:00",
  "color": "#40E0D0"
}, {
  "title": "Long Event",
  "start": "2016-01-07 00:00:00",
  "color": "#FF0000"
}, {
  "title": "Repeating Event",
  "start": "2016-01-09 16:00:00",
  "color": "#0071c5"
}, {
  "title": "Conference",
  "start": "2016-01-11 00:00:00",
  "color": "#40E0D0"
}, {
  "title": "Meeting",
  "start": "2016-01-12 10:30:00",
  "color": "#000"
}, {
  "title": "Lunch",
  "start": "2016-01-12 12:00:00",
  "color": "#0071c5"
}, {
  "title": "Happy Hour",
  "start": "2016-01-12 17:30:00",
  "color": "#0071c5"
}, {
  "title": "Dinner",
  "start": "2016-01-12 20:00:00",
  "color": "#0071c5"
}, {
  "title": "Birthday Party",
  "start": "2016-01-14 07:00:00",
  "color": "#FFD700"
}, {
  "title": "Double click to change",
  "start": "2016-01-28 00:00:00",
  "color": "#008000"
}, {
  "title": "512",
  "start": "2021-04-04 00:00:00",
  "color": "#FF0000"
}, {
  "title": "21512",
  "start": "2021-04-06 00:00:00",
  "color": "#FF0000"
}, {
  "title": "236234",
  "start": "2021-04-07 00:00:00",
  "color": "#FF0000"
}, {
  "title": "3521",
  "start": "2021-04-03 00:00:00",
  "color": "#00FF00"
}, {
  "title": "HHH",
  "start": "2021-04-02 00:00:00",
  "color": "#FFFF00"
}]
document.addEventListener('DOMContentLoaded', function() {
    var initialLocaleCode = 'lt';
    var dates = <?php echo json_encode($events); ?>;
  var localeSelectorEl = document.getElementById('locale-selector');
  var now = <?php echo json_encode($now); ?>;
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {

    buttonIcons: true,
    timezone: 'Europe/Kiev',
    navLinks: true,
    selectable: true,
    selectMirror: true,
    weekNumbers: true,
    initialView: 'dayGridMonth',
    nowIndicator: true,
    initialDate: '2021-04-25',
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
  locale: initialLocaleCode,
  eventDidMount: function(view) {
      //loop through json array
      $(arrays).each(function(i, val) {
        //find td->check if the title has same value-> get closest daygird ..change color there
        $("td[data-date=" + moment(val.start).format("YYYY-MM-DD") + "] .fc-event-title:contains(" + val.title + ")").closest(".fc-daygrid-event-harness").css("background-color", val.color);
      })
    },

    eventClick:  function(arg) {
                // endtime = $.fullCalendar.moment(event.end).format('h:mm');
                // starttime = $.fullCalendar.moment(event.start).format('dddd, MMMM Do YYYY, h:mm');
                // var mywhen = starttime + ' - ' + endtime;
                $('#title').html(arg.event.title);
                $('#modalWhen').text(arg.event.start);
                $('#eventID').val(arg.event.id);
                $('#calendarModal').modal();
            },
    editable: true,
    dayMaxEvents: true,
    events: arrays
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
