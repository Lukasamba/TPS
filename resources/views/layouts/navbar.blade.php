<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Home</title>
    </head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{route('HomePage')}}">TPS</a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="/teams">Komandos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/projects">Projektai</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/calendar">Kalendorius</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/calendarDummy">KalendoriusDummy</a>
              </li>
          </ul>
          <ul class="navbar-nav ms-auto d-flex">
            @if(session()->has('userName'))

            <li class="nav-item">
              <a class="nav-link">{{session()->get('userEmail')}}</a>
            </li>

            <li class="nav-item">
              <a href="{{route('signout')}}" class="nav-link">Atsijungti</a>
            </li>

            @else
              <li class="nav-item">
                <a href="{{route('signin')}}" class="nav-link">Prisijungti</a>
              </li>
            @endif
          </ul>
        </div>
      </div>
    </nav>
    <main role="main">
        @if(session('error'))
          <div class="alert alert-danger" role="alert">
            <p class="mb-3">{{ session('error') }}</p>
            @if(session('errorDetail'))
              <pre class="alert-pre border bg-light p-2"><code>{{ session('errorDetail') }}</code></pre>
            @endif
          </div>
        @endif

      </main>
      {{-- <div> --}}
        @yield('content')

      {{-- </div> --}}
      {{-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
      integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
      crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
      integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
      crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
      integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
      crossorigin="anonymous"></script> --}}
</body>
</html>
