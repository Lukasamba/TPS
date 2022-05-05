@extends('layouts.navbar')

@section('content')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" />
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

  <div id='top'>

    Locales:
    <select id='locale-selector'></select>
</div>
   <div id="calendar"></div>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
  <script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
  <script src="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js"></script>
  <script src=https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js></script>



  <script>
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
    weekNumbers: true,
    initialView: 'timeGridWeek',
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
  locale: initialLocaleCode,
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
