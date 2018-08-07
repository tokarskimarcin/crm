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
    .btn-danger {
       width: 100%;
    }
    .btn-warning {
        width: 100%;
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
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Źródła pracowników
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped thead-inverse">
                        <thead>
                            <th>Źródłó</th>
                            <th style="width: 15%">Edytuj</th>
                            <th style="width: 15%">Usuń / Przywróć</th>
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
                    <button class="btn btn-info btn-block" id="add_new_source">
                        <span class="glyphicon glyphicon-plus"></span> Dodaj źródło
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Administracja wynikami etapów
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control" id="selected_status">
                            <option>Wybierz</option>
                            @foreach($attempt_status as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-warning btn-block" id="check_result">
                            <span class="glyphicon glyphicon-ok"></span> Wybierz status
                        </button>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" id="selected_result">
                            <option>Wybierz</option>
                            @foreach($attempt_results as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-warning btn-block" id="add_result">
                            <span class="glyphicon glyphicon-ok"></span> Dodaj rezultat
                        </button>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped thead-inverse">
                            <thead>
                                <tr>
                                    <th>Rezultat</th>
                                    <th style="width: 20%">Usuń</th>
                                </tr>
                            </thead>
                            <tbody id="status_result">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="alert alert-danger" id="no_results" style="display: none">
                    Brak rezultatów dla danego etapu!
                </div>
            </div>
        </div>
    </div>
</div>

@php
    for($i = 1; $i <= 10; $i++) { 
        echo "<br />";
    }
@endphp

@endsection
@section('script')
<script>

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
                var deletedType = (value.deleted == 0) ? 'remove' : 'ok';
                var deletedColor = (value.deleted == 0) ? 'red' : 'green' ;

                content += `
                    <tr id="source${value.id}">
                        <td>${value.name}</td>
                        <td> 
                            <button class="btn btn-link" data-id="${value.id}" onclick="editSource(this)">
                                <span style="color: green" class="glyphicon glyphicon-pencil"></span>
                            </button> 
                        </td>
                        <td>
                            <button class="btn btn-link" data-id="${value.id}" data-deleted="${value.deleted}" onclick="deleteSource(this)">
                                <span style="color: ${deletedColor}" class="glyphicon glyphicon-${deletedType}"></span>
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
    getCandidateSource();

    //Funkcja dodająca nowe źródło
    $('#add_new_source').click(() => {
        //zablokowanie przycisku zapisującego pytanie
        $('#add_new_source').attr('disabled', 'disabled');

        //pobranie treści nowego etapu
        var newSource = $('#new_source').val();

        if (newSource == '' || (newSource.trim().length == 0)) {
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

//Funkcja edytująca źródło rekrutacji
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
    //Pobranie id źródła
    var id = $(e).data('id');
    //Pobranie nazwy źrodła
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

function deleteSource(e) {
    //Pobranie id źródła
    var id = $(e).data('id');
    //Pobranie statusu
    var deleted = $(e).data('deleted');

    deleted = (deleted == 0) ? 1 : 0;

    $.ajax({
        type: "POST",
        url: '{{ route('api.deleteCandidateSource') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            "id": id,
            "deleted": deleted
        }, success: function (response) {
            if (response == 1) {
                swal('Status źródła został zmieniony!');
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

$('#check_result').click((e) => {
    var selected_status = $('#selected_status').val();
    
    if (selected_status == 'Wybierz') {
        swal('Wybierz etap!');
        return false;
    }

    $('#status_result tr').remove();

    $.ajax({
        type: "POST",
        url: '{{ route('api.getStatusResults') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            "selected_status":selected_status
        },
        success: function (response) {
            content = "";

            $.each(response, function(key, value) {
                content += `
                    <tr>
                        <td>${value.name}</td>
                        <td>
                            <button class="btn btn-danger" onclick="statusDelete(this)" data-id="${value.id}">
                                <span class="glyphicon glyphicon-remove"></span> Usuń
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#status_result').append(content);

            if (content == '') {
                $('#no_results').fadeIn(500);
            } else {
                $('#no_results').fadeOut(500);
            }

        }, error: function(response) {
            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
        }
    });
});

$('#add_result').click(function(e) {
    var selected_status = $('#selected_status').val();
    var selected_result = $('#selected_result').val();

    if (selected_status == 'Wybierz') {
        swal('Wybierz etap!');
        return false;
    }

    if (selected_result == 'Wybierz') {
        swal('Wybierz rezultat!');
        return false;
    }
    $(e.target).prop('disabled',true);
    changeStatus('add', selected_status, selected_result, e.target);
});

function statusDelete(e) {
    var selected_result = $(e).data('id');
    var selected_status = $('#selected_status').val();

    swal({
        title: '',
        text: "Usunąć?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak'
        }).then((result) => {
            if (result.value) {
                changeStatus('delete', selected_status, selected_result,e);
            }
    });
}

function changeStatus(type, selected_status, selected_result, target = null) {
    console.log(target);
    $.ajax({
        type: "POST",
        url: '{{ route('api.statusResultChange') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            "type":type,
            "selected_status":selected_status,
            "selected_result":selected_result
        },
        success: function (response) {
            if (response == 'add') {
                swal('Dodano pomyślnie!');
            } else if (response == 'delete') {
                swal('Usunięto pomyślnie!');
            }else if(response == 'already_exists'){
                swal('Ten rezultat jest już dodany');
            }
            $(target).prop('disabled',false);
            $('#check_result').click();
        }, error: function(response) {
            $(target).prop('disabled',false);
            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
        }
    });
}

</script>
@endsection
