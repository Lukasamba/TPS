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
            </div>
            <div class="modal-body" id=".modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script>
    $('#exampleModal').on('show.bs.modal',
        function(event) {
            var allprojects = <?php echo json_encode($allprojects) ?>;
            var allMyProjects = <?php echo json_encode($allMyProjects) ?>;
            var teams = <?php echo json_encode($teams) ?>;
            var team_projects = <?php echo json_encode($team_projects) ?>;

            // Button that triggered the modal
            var li = $(event.relatedTarget)

            // Extract info from data attributes
            var projectId = li.data('id')
            var projectName = li.data('name')

            // Updating the modal content using
            // jQuery query selectors
            var modal = $(this)
            console.log(allMyProjects);

            modal.find('.modal-title')
                .text('Projektas: ' + projectName)

            var text = ""
            for (let i = 0; i < team_projects.length; i++) {
                if (team_projects[i].fk_projectId == projectId) {
                    team_id = team_projects[i].fk_teamId
                    text += "\n" + teams.find(team => team.teamId === team_id).teamName + ", \n";
                }
            }
            if (text == "") {
                text = "nėra"
            } else {
                text = text.slice(0, -3)
            }

            modal.find('.modal-body')
                .text('Komandos:\n' + text)
        })
</script>

<div style="margin: 10px auto; padding: 0 10px;">
    <a class="btn btn-secondary" href="/projects/new" padding="15px 32px">Sukurti projektą</a>
    <h3>Mano projektai</h3>
</div>

<table class="table">
    <thead>
        <tr>
            @if(count($allMyProjects) != 0)
            <th scope="col">Identifikatorius</th>
            <th scope="col">Projekto pavadinimas</th>
            @else
            <p>Projektų nėra</p>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($allMyProjects as $project)
        <tr>
            <td width="10%">{{$project->projectId}}</td>
            <td width="65%">{{$project->projectName}}</td>
            <td width="25%">
                <a href="#" class="view" title="View" data-desc="{{$project->projectDescription}}" data-name="{{$project->projectName}}"" data-id=" {{$project->projectId}}" data-toggle="modal" data-target="#exampleModal">
                    <i class="material-icons">&#xE417;</i></a>
                <a class="btn btn-primary" href="/eventGenerate/single/{{$project->projectId}}">Generuoti įvyki</a>
                <a class="btn btn-primary" href="/eventGenerate/sprint/{{$project->projectId}}">Generuoti sprintą</a>
            </td>
        </tr>
        @empty
        @endforelse
    </tbody>
</table>

<div style="margin: 10px auto; padding: 0 10px;">
    <h3>Visi projektai</h3>
</div>

<table class="table">
    <thead>
        @if(count($allprojects) != 0)
        <th scope="col">Identifikatorius</th>
        <th scope="col">Projekto pavadinimas</th>
        @else
        <p>Projektų nėra</p>
        @endif
    </thead>
    <tbody>
        @forelse ($allprojects as $project)
        <tr>
            <td width="10%">{{$project->projectId}}</td>
            <td width="80%">{{$project->projectName}}</td>
            <td width="10%">
                <a href="#" class="view" title="View" data-desc="{{$project->projectDescription}}" data-name="{{$project->projectName}}"" data-id=" {{$project->projectId}}" data-toggle="modal" data-target="#exampleModal">
                    <i class="material-icons">&#xE417;</i></a>
            </td>
        </tr>
        @empty
        @endforelse
    </tbody>
</table>

</div>

@endsection
