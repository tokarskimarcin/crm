@extends('layouts.main')
@section('content')
    <style>
        .inactive {
            display: none;
        }
    </style>

    @php
    $licznik = 1;
    @endphp

    <div class="container">
        <div class="panel">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <div class="alert gray-nav">Dodawanie/usuwanie przywilejów dla użytkowników</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Nazwa panelu
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 style="text-align:center;">Co chcesz zrobic?</h2>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input_container" style="margin-bottom:2em;">
                                <label for="privilage_people">Tylko ludzie posiadający specjalne uprawnienia</label>
                                <input type="checkbox" checked style="display:inline-block;" id="privilage_people">
                            </div>
                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>ID użytkownika</th>
                                        <th>Imie i Nazwisko</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID użytkownika</th>
                                    <th>Nazwa</th>
                                    <th>Link id</th>
                                </tr>
                                </thead>
                                <tbody class="append_row">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--<form action="" method="POST">--}}
                        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="users">Wybierz użytkownika</label>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function(e) {
            var isChecked = 0;
            document.getElementById('privilage_people').addEventListener('change', function(e) {
               isChecked = e.target.checked;
               sessionStorage.setItem('isChecked', isChecked);
               table.ajax.reload();
            });

            table = $('#datatable').DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "ajax": {
                    'url': "{{ route('api.privilageAjax') }}",
                    'type': 'POST',
                    'data': {
                        "privilage_people": isChecked
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"columns":[
                    {"data": "user_id"},
                    {"data": function (data, type, dataToSet) {
                            let name = data.first_name;
                            let surname = data.last_name;
                            return name + ' ' + surname;
                        }}
                ]
            });

        });
    </script>
@endsection
