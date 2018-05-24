@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

    <style>
        .heading-container {
            text-align: center;
            font-size: 2em;
            margin: 1em;
            font-weight: bold;
            box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
            padding-top: 1em;
            padding-bottom: 1em;
        }

        .form-container {
            box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
            padding-top: 1em;
            padding-bottom: 1em;
            margin: 1em;
        }
    </style>

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Panel zarządzania miastami</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Miasta
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button data-toggle="modal" class="btn btn-default cityToModal" id="NewCityModal" data-target="#ModalCity" data-id="1" title="Nowe Miasto" style="margin-bottom: 14px">
                                <span class="glyphicon glyphicon-plus"></span> <span>Dodaj Miasto</span>
                            </button>
                            <div>
                                <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Wojweództwo</th>
                                                <th>Miasto</th>
                                                <th>Ilość Pokazów</th>
                                                <th>Karencja</th>
                                                <th>Edycja</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="ModalCity" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title">Dodawanie Miasta<span id="modalCity"></span></h4>
                </div>
                <div class="modal-body">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Legenda
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Województwo:</label>
                                                <select class="form-control" id="voiovedshipID">
                                                    @foreach($allVoivodeship as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Miasto:</label>
                                            <input class="form-control" id="cityName" name="cityName" placeholder="Miasto" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Ilość pokazów:</label>
                                            <input class="form-control" id="eventCount" name="eventCount" placeholder="Ilość pokazów" type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Karencja:</label>
                                            <input class="form-control" id="gracePeriod" name="gracePeriod" placeholder="Karencja" type="number" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-success form-control" id="saveCityModal" onclick = "saveCity(this)">Dodaj Miasto</button>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="alert alert-success">--}}
                                {{--<h4>--}}
                                    {{--<p>Aktualny wynik wyliczany jest na podstawie ostatnich ~18 RBH danego konsultanta.</p>--}}
                                    {{--<p>W przypadku gdy, aktualny wynik jest większy niż 0.5, wymagane jest aby wynik docelowy mieścił się w przedziale od 10% do 30% aktualnego wyniku.</p>--}}
                                    {{--<p>Konsultant wyświetli się na liście, po zaakceptowaniu przynajmniej jednej godziny.</p>--}}
                                {{--</h4>--}}
                            {{--</div>--}}

                        </div>
                    </div>
                    <div class="row">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>

        function saveCity(e) {
            let voiovedshipID = $('#voiovedshipID').val();
            let cityName = $('#cityName').val();
            let eventCount = $('#eventCount').val();
            let gracePeriod = $('#gracePeriod').val();
            let validation = true;
            console.log(gracePeriod+' '+eventCount+' '+cityName+' '+voiovedshipID);

            if(validation){
                $.ajax({
                    type: "POST",
                    url: "{{route('api.saveCoachingDirector')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'manager_id'                    : manager_id,

                    },
                    success: function (response) {
                        console.log(response);
                        $('#Modal_Coaching').modal('hide');
                    }
                })
            }
        }
      $(document).ready(function () {
          table = $('#datatable').DataTable({
              "autoWidth": true,
              "processing": true,
              "serverSide": true,
              "drawCallback": function( settings ) {
              },
              "ajax": {
                  'url': "{{ route('api.getCity') }}",
                  'type': 'POST',
                  'data': function (d) {
                      // d.date_start = $('#date_start').val();
                  },
                  'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
              },
              "language": {
                  "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
              },"columns":[
                  {"data":"vojName"},
                  {"data":"name"},
                  {"data":"max_hour"},
                  {"data":"grace_period"},
                  {"data":function (data, type, dataToSet) {
                          return '<button class="btn btn-info" >Edycja</button>';
                      },"orderable": false, "searchable": false
                  }
              ]
          });
      })
    </script>
@endsection
