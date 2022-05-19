@extends('layouts.navbar')

@section('content')

<head>
    <link href="{{ asset('css/team.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>jQuery UI Autocomplete - Default functionality</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
    $(function() {
        var names = @json($names);
        var emails = @json($emails);

        // for future maybe, to have full gmail/outlook search type functionality
        // var mass = names.concat(emails);

        function split(val) {
            return val.split(/,\s*/);
        }

        function extractLast(term) {
            return split(term).pop();
        }

        $("#names")
            // don't navigate away from the field on tab when selecting an item
            .on("keydown", function(event) {
                if (event.keyCode === $.ui.keyCode.TAB &&
                    $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 0,
                source: function(request, response) {
                    // delegate back to autocomplete, but extract the last term
                    response($.ui.autocomplete.filter(
                        names, extractLast(request.term)));
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function(event, ui) {
                    var terms = split(this.value);
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push(ui.item.value);
                    // add placeholder to get the comma-and-space at the end
                    terms.push("");
                    this.value = terms.join(", ");
                    return false;
                }
            });
    });
    </script>
</head>

<body>
    <br>
    <div class="main">
        <h1>Komandos {{ $team->teamName}} redagavimas</h1>
    </div>

    <br>
    <div class="main">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nario vardas</th>
                    <th></th>
            </thead>
            @foreach($teams as $team)

            <tbody class="table-group-divider">
                <tr>
                    <td>{{$team->teamId}}</td>
                    <td>{{$team->teamName}}</td>
                    <td>
                        <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i
                                class="material-icons">&#xE872;</i></a>
                    </td>
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>

    <form action="/team/{{ $team->teamId }}">
        @method('PUT')

        @csrf


    </form>

    <div class="main">
        <a href="/teams">Atgal</a>
    </div>
</body>

@endsection