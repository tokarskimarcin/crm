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
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Źródła pracowników
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped thead-inverse">
                        <thead>
                            <th>Żródłó</th>
                            <th style="width: 15%">Edytuj</th>
                            <th style="width: 15%">Usuń / Przywróc</th>
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

</script>
@endsection
