@extends('layouts.navbar')

@section('content')

<head>
    <link href="{{ asset('css/team.css') }}" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id=".modal-title"></h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body" id=".modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
            </div>
        </div>
    </div>
</div>

<body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        $('#exampleModal').on('show.bs.modal',
            function(event) {
                var teams = <?php echo json_encode($teams) ?>;
                var users = <?php echo json_encode($users) ?>;
                var team_members = <?php echo json_encode($team_members) ?>;

                // Button that triggered the modal
                var li = $(event.relatedTarget)

                // Extract info from data attributes
                var teamId = li.data('id')
                var teamName = li.data('name')

                // Updating the modal content using
                // jQuery query selectors
                var modal = $(this)

                modal.find('.modal-title')
                    .text('Komanda: ' + teamName)

                var text = ""
                for (let i = 0; i < team_members.length; i++) {
                    if (team_members[i].fk_teamId == teamId) {
                        text += "\n" + users[team_members[i].fk_userId - 1].userName + ", \n";
                    }
                }
                if (text == "") {
                    text = "nėra"
                } else {
                    text = text.slice(0, -3)
                }

                modal.find('.modal-body')
                    .text('Komandos nariai:\n' + text)
            })
    </script>

    <div style="margin: 10px auto; padding: 0 10px;">
        <a class="btn btn-secondary" href="/teams/create" padding="15px 32px">Sukurti komandą</a>
        <h3>Komandų sąrašas</h3>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Identifikatorius</th>
                <th scope="col">Komandos pavadinimas</th>
                <th></th>
        </thead>

        <tbody>
            @foreach($teams as $team)
            <tr>
                <td width="10%">{{$team->teamId}}</td>
                <td width="80%">{{$team->teamName}}</td>
                <td width="10%">
                    <a href="#" class="view" title="View" data-name="{{$team->teamName}}"" data-id=" {{$team->teamId}}" data-toggle="modal" data-target="#exampleModal">
                        <i class="material-icons">&#xE417;</i></a>
                    <!-- <a href="#" class="edit" title="Edit" data-toggle="tooltip">
                            <i class="material-icons">&#xE254;</i></a>
                        <a href="#" class="delete" title="Delete" data-toggle="tooltip">
                            <i class="material-icons">&#xE872;</i></a> -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

@endsection
