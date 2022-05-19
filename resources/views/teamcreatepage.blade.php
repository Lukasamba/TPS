@extends('layouts.navbar')

@section('content')

<head>
    <link href="{{ asset('css/team.css') }}" rel="stylesheet">

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
        <h1>Sukurti komandą</h1>
        <div>
            <h4>Komandos pavadinimas</h4>
        </div>
        <input type="text" id="name" name="teamName" placeholder="Pavadinimas">
        <div>
            <br>
            <h4>Įrašyti narius</h4>
        </div>
        <div class="ui-widget">
            <input type="search" name="query" id="names" placeholder="Vardenis Pavardenis, ...">
        </div>
        <br>
        <button> Sukurti </button>

    </div>
    <br>

</body>

@endsection

<!-- nav>ul>li tab tab -->