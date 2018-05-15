@extends('layouts.main')
@section('content')
    <style>
        .inactive {
            display: none;
        }

        .glyphicon-remove {
            transition: all 0.8s ease-in-out;
        }
        .glyphicon-remove:hover {
            transform: scale(1.2) rotate(180deg);
            cursor: pointer;
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

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Nazwa panelu
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input_container" style="margin-bottom:2em;">
                                <label for="privilage_people">Tylko ludzie posiadający specjalne uprawnienia</label>
                                <input type="checkbox" checked style="display:inline-block;" id="privilage_people">
                            </div>
                            <form action="{{URL::to('/userPrivilages')}}" method="POST" id="formularz">


                            </form>
                            <table class="table-striped table-hover" id="datatable">
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
                                    <th>Link ID</th>
                                    <th>Nazwa</th>
                                    <th>Usuń</th>
                                </tr>
                                </thead>
                                <tbody id="append_row">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function(e) {
            document.getElementById('privilage_people').checked = false;
            var isChecked = false;
            var user_id;

            /**
             * This event listener change value of variable isChecked when checkbox is checked or not
             */
            document.getElementById('privilage_people').addEventListener('change', function(e) {
               isChecked = e.target.checked;
               sessionStorage.setItem('isChecked', isChecked);
               table.ajax.reload();
            });

            /**
             * Method left table
             * @type {*|jQuery}
             */
            table = $('#datatable').DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "ajax": {
                    'url': "{{ route('api.privilageAjax') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.privilage_people = isChecked;
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
                    },"name": "last_name"}
                ]
            });

            /**
             * This multi level event listener is responsible for right table.
             */
            $( document ).ajaxComplete(function(event, xhr, settings) {
                if(settings.url === "{{ route('api.privilageAjax') }}") {
                    let first_table_rows = Array.from(document.querySelectorAll('#datatable tr'));
                    first_table_rows.forEach(function(row) {
                        row.addEventListener('click', function(e) {
                            document.getElementById('formularz').textContent = ''; //clearing adding new privilage form
                            user_id = e.target.parentNode.firstChild.textContent;
                            document.getElementById('append_row').textContent = '';
                            let clicked_row = e.target;
                            let id_of_user = e.target.parentNode.firstChild.textContent;

                            $('#formularz').append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
                            $('#formularz').append('<input type="hidden" name="user_id" value="' + user_id + '">');
                            $('#formularz').append('<input type="hidden" name="isAdding" value="true">');
                            $('#formularz').append('<div class="form-group"><label for="add_new_privilage">Dodaj nowy dostęp dla ' + e.target.parentNode.lastChild.textContent + ': </label></div>');
                            $('#formularz').append('<select id="add_new_privilage" class="form-control" name="add_new_privilage">');
                            @php
                                $i = 2;
                            @endphp
                            @if($i < $all_links->count())
                                @foreach($all_links as $link)
                                    $('#add_new_privilage').append('<option value="{{$link->id}}"> {{$link->name}} </option>');
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            @else
                                $('#formularz').append('</select>');
                            @endif

                            $('#formularz').append('<input class="btn btn-success btn-block" type="submit" value="Dodaj nowy dostęp!" style="margin-bottom:1em;margin-top:1em;">');
                            $.ajax({
                                type: "POST",
                                url: '{{ route('api.privilageAjaxData') }}',
                                data: {
                                    "id_of_user": id_of_user
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    for(var i = 0; i < response.length; i++) {
                                        $('#append_row').append('<tr><td>' + response[i].link_id + '</td><td>' + response[i].name + '</td><td><span style="font-size:2em;color:red;" class="glyphicon glyphicon-remove gl"></span></td></tr>');
                                    }
                                }
                            });
                        });
                    });
                }

            });

            /**
             * This event listener is responsible for deleting privilages
             */
            $(document).ajaxComplete(function(event, xhr, settings) {
                if(settings.url === "{{ route('api.privilageAjaxData') }}") {
                    let remove_glyphicons = Array.from(document.querySelectorAll('.gl'));
                    remove_glyphicons.forEach(function(icon) {
                       icon.addEventListener('click', function(event) {

                           swal({
                               title: 'Jesteś pewien?',
                               text: "Po potwierdzeniu, brak możliwości cofnięcia zmian!",
                               type: 'warning',
                               showCancelButton: true,
                               confirmButtonColor: '#3085d6',
                               cancelButtonColor: '#d33',
                               confirmButtonText: 'Usuń!'
                           }).then((result) => {
                               if (result.value) {
                               let remove_id = event.target.parentNode.parentNode.firstChild.textContent;
                               $('#formularz').append('<input type="hidden" name="remove_privilage_id" value="' + remove_id + '">');
                               $('#formularz').append('<input type="hidden" name="user_id" value="' + user_id + '">');
                               $('#formularz').append('<input type="hidden" name="isAdding" value="false">');
                               $('#formularz').append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
                               document.querySelector('#formularz').submit();
                               swal(
                                   'Usunięte!',
                                   'Kryterium zostało usunięte',
                                   'Sukces'
                               )
                           }
                       });

                       });
                    });
                }
            });


        });
    </script>
@endsection
