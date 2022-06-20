<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <title>Welcome</title>
  <style>
    html, body {
      background: url(https://aadcdn.msftauth.net/shared/1.0/content/images/backgrounds/2_bc3d32a696895f78c19df6c717586a5d.svg) no-repeat center center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
      margin: 0;
      height: 100%;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow my-5">
          <div class="card-body p-4 p-sm-5">
          <h2 class="text-center mb-3">Laiko Planavimo Sistema</h2>
          <h5 class="card-title text-center mb-4 fw-light fs-5">Sveiki sugrįžę!</h5>
            <div class="d-grid mb-3">
              <a class="btn btn-primary btn-login fw-bold" href="{{route('HomePage')}}">Prisijungti su Outlook</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>