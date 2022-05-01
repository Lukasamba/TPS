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
        <a class="navbar-brand" href="/">TPS</a>
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
          </ul>
          <ul class="navbar-nav ms-auto d-flex">
            @if (!Session::has('userInfo'))
                
            <li class="nav-item">
              <a class="nav-link" href="#">Prisijungti</a>
            </li>

            @else

            <li class="nav-item">
              <a class="nav-link" href="#">Atsijungti</a>
            </li>
            
            @endif
          </ul>
        </div>
      </div>
    </nav>

    @yield('content')
    
</body>
</html>