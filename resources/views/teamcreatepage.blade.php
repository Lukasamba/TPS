@extends('layouts.navbar')

@section('content')

{{-- Klaidų metimui --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<head>
    <!-- <link href="{{ asset('css/team.css') }}" rel="stylesheet"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>jQuery UI Autocomplete - Default functionality</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        $(function() {oninvalid="this.setCustomValidity('Lütfen işaretli yerleri doldurunuz')"}) ;
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
    <div style="max-width: 1100px;
    margin: 40px auto;
    padding: 0 10px;">
        <h1>Sukurti komandą</h1>
        <form action="{{ url('save-team') }}" method="POST">
            @csrf

            <div class="form-group">
                <br>
                <label>Komandos pavadinimas:</label>
                <div class="row">
                    <div class="col-sm">
                    <input type="text" class="form-control" id="name" name="teamName" placeholder="Pavadinimas" required oninvalid="this.setCustomValidity('Prašome įvesti pavadinimą :)')">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <br>
                <label>Įrašyti narius:</label>
                <div class="row">
                    <div class="col-sm">
                    <input type="search" class="form-control" name="query" id="names" placeholder="Vardenis Pavardenis, ..." required oninvalid="this.setCustomValidity('Įveskite ')">
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Sukurti</button>
            <a class="btn btn-secondary" href=/teams>Atšaukti</a>
        </form>
    </div>
    <br>

</body>

@endsection

<!-- nav>ul>li tab tab -->