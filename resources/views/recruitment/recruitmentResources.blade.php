@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
    .btn {
        outline: none !important;
        box-shadow: none !important;
   }
   .btn-info {
       width: 100%;
   }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Zasoby rekrutacji</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Etapy rekrutacji
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped thead-inverse">
                        <thead>
                            <th>Etap</th>
                            <th>Edytuj</th>
                            <th>Usuń</th>
                        </thead>
                        <tbody id="levels">
                            
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label>Dodaj etap rekrutacji</label>
                    <input type="text" class="form-control" placeholder="Kolejny etap..." id="new_attempt_level"/>
                </div>
                <div class="form-group">
                    <button class="btn btn-info" id="add_new_level">
                        <span class="glyphicon glyphicon-plus"></span> Dodaj etap
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Źródła pracowników
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped thead-inverse">
                        <thead>
                            <th>Żródłó</th>
                            <th>Edytuj</th>
                            <th>Usuń</th>
                        </thead>
                        <tbody id="sources">
                            
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label>Dodaj źródło</label>
                    <input type="text" class="form-control" placeholder="Kolejny etap..." id="new_source"/>
                </div>
                <div class="form-group">
                    <button class="btn btn-info" id="add_new_source">
                        <span class="glyphicon glyphicon-plus"></span> Dodaj żródło
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<script>

var fetchData = {
    method: 'post',
    credentials: "same-origin",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
}

function getAttemptLevels() {
    $('#levels tr').remove();
    fetch('{{ route('api.getAttemptLevel') }}',fetchData)
    .then((res) => res.json())
    .then((data) => {
        var content = ""

        $.each(data, (key, value) => {
            content += `
                <tr id="level${value.id}">
                    <td>  ${value.name} </td>
                    <td> 
                        <button class="btn btn-link" data-id="${value.id}" onclick="edit_level(this)">
                            <span style="color: green" class="glyphicon glyphicon-pencil"></span>
                        </button> 
                    </td>
                    <td>
                        <button class="btn btn-link" data-id="${value.id}" onclick="deleteLevel(this)">
                            <span style="color: red" class="glyphicon glyphicon-remove"></span>
                        </button>  
                    </td>
                </tr>
            `;
        });
        $('#levels').append(content);
    })
}

function getCandidateSource() {
    $('#sources tr').remove();

    $.ajax({
        type: "POST",
        url: '{{ route('api.getCandidateSource') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {},
        success: function (response) {
            var content = "";
            $.each(response, (key, value) => {
                content += `
                    <tr id="source${value.id}">
                        <td>  ${value.name} </td>
                        <td> 
                            <button class="btn btn-link" data-id="${value.id}" onclick="editSource(this)">
                                <span style="color: green" class="glyphicon glyphicon-pencil"></span>
                            </button> 
                        </td>
                        <td>
                            <button class="btn btn-link" data-id="${value.id}" onclick="deleteSource(this)">
                                <span style="color: red" class="glyphicon glyphicon-remove"></span>
                            </button>  
                        </td>
                    </tr>
                `;
            })
            
            $('#sources').append(content);
        }, error: function(response) {
            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
        }
    });
}

$(document).ready(() => {
    //Pobranie danych po załadowaniu strony
    getAttemptLevels();
    getCandidateSource();
    

    //Funkcja dodająca nowy etap rekrutacji
    $('#add_new_level').click(() => {
        //zablokowanie przycisku zapisującego pytanie
        $('#add_new_level').attr('disabled', 'disabled');

        //pobranie treści nowego etapu
        var newLevel = $('#new_attempt_level').val();

        if (newLevel == '') {
            swal('Podaj nazwę etapu!')
            $('#add_new_level').attr('disabled', false);
            return false;
        }

        //Wysłanie danych na server
        $.ajax({
            type: "POST",
            url: '{{ route('api.addAttemptLevel') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "name": newLevel
            }, success: function (response) {
                if (response == 1) {
                    swal('Nowy etap został dodany!');
                    getAttemptLevels();
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
        //Odblokowanie przycisku
        $('#add_new_level').attr('disabled', false);
        //Wyczyszczenie pola formularza
        $('#new_attempt_level').val('');
    });

    //Funkcja dodająca nowe źródło
    $('#add_new_source').click(() => {
        //zablokowanie przycisku zapisującego pytanie
        $('#add_new_source').attr('disabled', 'disabled');

        //pobranie treści nowego etapu
        var newSource = $('#new_source').val();

        if (newSource == '') {
            swal('Podaj nazwę źródła!')
            $('#add_new_source').attr('disabled', false);
            return false;
        }

        //Wysłanie danych na server
        $.ajax({
            type: "POST",
            url: '{{ route('api.addCandidateSource') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "name": newSource
            }, success: function (response) {
                if (response == 1) {
                    swal('Nowe źródło zostało dodane!');
                    getCandidateSource();
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
        //Odblokowanie przycisku
        $('#add_new_source').attr('disabled', false);
        //Wyczyszczenie pola formularza
        $('#new_source').val('');
    });
});


//Funkcja edytująca etap rekrutacji
function edit_level(e) {
    //Pobranie id etapu
    var id = $(e).data('id');
    //Pobranie nazwy etapu
    var oldLevel = $('#level' + id).find('td:first').html();

    //Podmiana tresci etapu na input zawierający treść
    $('#level' + id).find('td:first').html('<input type="text" class="form-control" value="' + oldLevel + '"/>');
    //Podmiana ikony
    $(e).find('span').removeClass('glyphicon-pencil').addClass('glyphicon-envelope');
    //podmiana funcki wywołanej przy kliknięciu
    $(e).removeAttr('onclick').attr('onclick', 'saveLevel(this)');

}

function saveLevel(e) {
    //Pobranie id etapu
    var id = $(e).data('id');
    //Pobranie nazwy etapu
    var newName = $('#level' + id).find('input').val();

    $.ajax({
        type: "POST",
        url: '{{ route('api.editAttemptLevel') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            "id": id,
            "name": newName
        }, success: function (response) {
            if (response == 1) {
                swal('Nazwa etapu została zmieniona!');
            } else {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        }, error: function(response) {
            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
        }
    });
    //Reload danych
    getAttemptLevels();
}

function deleteLevel(e) {
    //Pobranie id etapu
    var id = $(e).data('id');

    swal({
        title: '',
        text: "Usunąć ten etap?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak'
        }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: '{{ route('api.deleteAttemptLevel') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "id": id
                }, success: function (response) {
                    if (response == 1) {
                        swal('Etap został usunięty!');
                    } else {
                        swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                    }
                }, error: function(response) {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            });
            //Reload danych
            getAttemptLevels();
        }
    })    
}

//Funkcja edytująca etap rekrutacji
function editSource(e) {
    //Pobranie id etapu
    var id = $(e).data('id');
    //Pobranie nazwy etapu
    var oldSource = $('#source' + id).find('td:first').html();

    //Podmiana tresci etapu na input zawierający treść
    $('#source' + id).find('td:first').html('<input type="text" class="form-control" value="' + oldSource + '"/>');
    //Podmiana ikony
    $(e).find('span').removeClass('glyphicon-pencil').addClass('glyphicon-envelope');
    //podmiana funcki wywołanej przy kliknięciu
    $(e).removeAttr('onclick').attr('onclick', 'saveSource(this)');

}

function saveSource(e) {
    //Pobranie id etapu
    var id = $(e).data('id');
    //Pobranie nazwy etapu
    var newName = $('#source' + id).find('input').val();

    $.ajax({
        type: "POST",
        url: '{{ route('api.editCandidateSource') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            "id": id,
            "name": newName
        }, success: function (response) {
            if (response == 1) {
                swal('Nazwa źródła została zmieniona!');
            } else {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        }, error: function(response) {
            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
        }
    });
    //Reload danych
    getCandidateSource();
}

</script>
@endsection
