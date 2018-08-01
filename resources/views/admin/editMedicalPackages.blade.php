@extends('layouts.main')
@section('content')

<style>
    .myLabel {
        font-size: 20px;
        color: #aaa;
    }
    .myButton {
        width: 100%;
        margin-top: 33px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Panel administratorski / Edycja pakietów medycznych</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Rok:</label>
            <select class="form-control" id="year_selected">
                <option value="2018">2018</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Miesiąc:</label>
            <select class="form-control" id="month_selected">
                @foreach($months as $month)
                    <option @if($month['id'] == date('m')) selected @endif value="{{$month['id']}}">{{$month['name']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <button style="width: 100%; margin-top: 24px;" class="btn btn-warning" id="refreash_table">
                <span class="glyphicon glyphicon-plus"></span> Wybierz
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Szukaj" id="search_input"/>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped thead-inverse">
                <thead>
                    <tr>
                        <th>Data dodania</th>
                        <th>Imie</th>
                        <th>Nazwisko</th>
                        <th>Data start</th>
                        <th>Data stop</th>
                        <th>Edycja</th>
                    </tr>
                </thead>
                <tbody id="data_input">

                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edytuj pakiet</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="package_id" value=""/>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Imie:</label>
                            <input type="text" class="form-control" id="user_first_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Nazwisko:</label>
                            <input type="text" class="form-control" id="user_last_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Pesel:</label>
                            <input type="number" class="form-control" id="pesel">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Data urodzienia:</label>
                            <input type="text" class="form-control" id="birth_date">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Miasto:</label>
                            <input type="text" class="form-control" id="city">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Ulica:</label>
                            <input type="text" class="form-control" id="street">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Numer domu:</label>
                            <input type="number" class="form-control" id="house_number">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Numer mieszkania:</label>
                            <input type="number" class="form-control" id="flat_number">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Kod pocztowy:</label>
                            <input type="text" class="form-control" id="postal_code">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Nr tel:</label>
                            <input type="number" class="form-control" id="phone_number">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Pakiet:</label>
                            <input type="text" class="form-control" id="package_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Wariant:</label>
                            <input type="text" class="form-control" id="package_variable">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Id użytkownika:</label>
                            <input type="number" class="form-control" id="user_id">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Zakres:</label>
                            <input type="text" class="form-control" id="package_scope">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Rozpoczęcie:</label>
                            <input type="text" class="form-control" id="month_start">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Zakończenie:</label>
                            <input type="text" class="form-control" id="month_stop">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Usunięty:</label>
                            <select class="form-control" id="deleted">
                                <option value="0">Nie</option>
                                <option value="1">Tak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Usunięty trwale:</label>
                            <select class="form-control" id="hard_deleted">
                                <option value="0">Nie</option>
                                <option value="1">Tak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" id="medical_scan_link">

                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success myButton" id="save_changes">
                            <span class="glyphicon glyphicon-envelope"></span> Zapisz zmiany
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')

<script>

function getData() {
    var year_selected = $('#year_selected').val();
    var month_selected = $('#month_selected').val();

    $.ajax({
        type: "POST",
        url: '{{ route('api.getMedicalPackagesAdminData') }}',
        data: {
            "year_selected":year_selected,
            "month_selected":month_selected
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#data_input tr').remove();

            content = '';

            $.each(response, function(key, value) {
                var month_stop = (value.month_stop != null) ? value.month_stop : 'Brak' ;
                content += `
                    <tr>
                        <td>${value.created_at}</td>
                        <td>${value.user_first_name}</td>
                        <td>${value.user_last_name}</td>
                        <td>${value.month_start}</td>
                        <td>${month_stop}</td>
                        <td>
                            <button class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="dataEdit(this)" data-id="${value.id}">
                                <span class="glyphicon glyphicon-plus"></span> Edytuj
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#data_input').append(content);
        },
        error: function(response) {
            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!');
        }
    });

}

function dataEdit(e) {
    var id = $(e).data('id');

    $.ajax({
        type: "POST",
        url: '{{ route('api.getMedicalPackageData') }}',
        data: {
            "id":id
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            $('#medical_scan_link a').remove();

            $('#package_id').val(response.id);
            $('#user_first_name').val(response.user_first_name);
            $('#user_last_name').val(response.user_last_name);
            $('#pesel').val(response.pesel);
            $('#birth_date').val(response.birth_date);
            $('#city').val(response.city);
            $('#street').val(response.street);
            $('#house_number').val(response.house_number);
            $('#flat_number').val(response.flat_number);
            $('#postal_code').val(response.postal_code);
            $('#phone_number').val(response.phone_number);
            $('#package_name').val(response.package_name);
            $('#package_variable').val(response.package_variable);
            $('#user_id').val(response.user_id);
            $('#package_scope').val(response.package_scope);
            $('#month_start').val(response.month_start);
            $('#month_stop').val(response.month_stop);

            if (response.deleted == 1){
                $('#deleted option[value="1"]').prop('selected', 'selected');
            } else {
                $('#deleted option[value="0"]').prop('selected', 'selected');
            }

            if (response.hard_deleted == 1){
                $('#hard_deleted option[value="1"]').prop('selected', 'selected');
            } else {
                $('#hard_deleted option[value="0"]').prop('selected', 'selected');
            }

            var fucking_string = '<a class="btn btn-info myButton" href="/api/getMedicalScan/' + response.scan_path + '" download="' + response.scan_path + '">Pobierz skan umowy</a>';

            $('#medical_scan_link').append(fucking_string);

        },
        error: function(response) {
            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!');
        }
    });
}

$(document).ready(function() {

    $('#save_changes').click(function(e) {

        $.ajax({
            type: "POST",
            url: '{{ route('api.saveMedicalPackageData') }}',
            data: {
                "package_id": $('#package_id').val(),
                "user_id": $('#user_id').val(),
                "user_first_name": $('#user_first_name').val(),
                "user_last_name": $('#user_last_name').val(),
                "pesel": $('#pesel').val(),
                "birth_date": $('#birth_date').val(),
                "city": $('#city').val(),
                "street": $('#street').val(),
                "house_number": $('#house_number').val(),
                "flat_number": $('#flat_number').val(),
                "postal_code": $('#postal_code').val(),
                "phone_number": $('#phone_number').val(),
                "package_name": $('#package_name').val(),
                "package_variable": $('#package_variable').val(),
                "package_scope": $('#package_scope').val(),
                "month_start": $('#month_start').val(),
                "month_stop": $('#month_stop').val(),
                "deleted": $('#deleted').val(),
                "hard_deleted": $('#hard_deleted').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response == 1) {
                    swal('Zmiany zapisano!');
                    getData();
                }
            },
            error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!');
            }
        });
    });

    getData();

    $('#refreash_table').click((e) => {
        getData();
    });

    $("#search_input").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#data_input tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

});

</script>
@endsection
