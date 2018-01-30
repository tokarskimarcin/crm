@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Administracja danych</div>
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
    fetch('{{ route('api.getAttemptLevel') }}',fetchData)
    .then((res) => res.json())
    .then((data) => {
        var content = ""

        $.each(data, (key, value) => {
            content += `
                <tr>
                    <td>  ${value.name} </td>
                    <td> ${value.id} </td>
                    <td> ${value.id} </td>
                </tr>
            `;
        });
        $('#levels').append(content);
    })
}

function addAttemptLevel() {
    $.ajax('{{ route('api.addAttemptLevel') }}',{
        method: 'post',
        mode: 'no-cors',
        credentials: "same-origin",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: new FormData({
            'name': "my name",
            'age': 12
        })
    })
    .then((res) => res.json())
    .then((data) => {
        console.log(data);
    })
}

$(document).ready(() => {
    getAttemptLevels();
    addAttemptLevel();
});

</script>
@endsection
