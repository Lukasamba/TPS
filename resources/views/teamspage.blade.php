@extends('layouts.navbar')

@section('content')

<head>
    <link href="{{ asset('css/team.css') }}" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
    $('#exampleModal').on('show.bs.modal',
        function(event) {

            // Button that triggered the modal
            var li = $(event.relatedTarget)

            // Extract info from data attributes 
            var recipient = li.data('whatever')

            // Updating the modal content using 
            // jQuery query selectors
            var modal = $(this)

            modal.find('.modal-title')
                .text('New message to ' + recipient)

            modal.find('.modal-body p')
                .text('Welcome to ' + recipient)
        })
    </script>

    <br>
    <div class="main">
        <h1>Komandų sąrašas</h1>
    </div>

    <br>
    <div class="main">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Komandos pavadinimas</th>
                    <th></th>
            </thead>
            @foreach($teams as $team)
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{$team->teamName}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
                        </div>
                    </div>
                </div>
            </div>
            <tbody class="table-group-divider">
                <tr>
                    <td>{{$team->teamId}}</td>
                    <td>{{$team->teamName}}</td>
                    <td>
                        <a href="#" class="view" title="View" data-toggle="modal" data-target="#exampleModal">
                            <i class="material-icons">&#xE417;</i></a>
                        <!-- <a href="#" class="edit" title="Edit" data-toggle="tooltip">
                            <i class="material-icons">&#xE254;</i></a>
                        <a href="#" class="delete" title="Delete" data-toggle="tooltip">
                            <i class="material-icons">&#xE872;</i></a> -->
                    </td>
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>

    <div class="main">
        <a href="/teams/create">Sukurti komandą</a>
    </div>



</body>
@endsection