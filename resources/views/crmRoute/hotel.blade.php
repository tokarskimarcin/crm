{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view allows user to edit given hotel (DB table: "hotels"),--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: hotelGet, hotelPost--}}
{{--*/--}}

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
            <div class="alert gray-nav ">Edytuj Hotel</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Formularz edycji hotelu
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="heading-container">
                                Edytuj hotel
                            </div>
                        </div>
                    </div>
                    <div class="form-container">
                        <form id="formToSubmitt" action="{{URL::to('/hotel/'. $id)}}" method="POST" id="formularz">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @if(Session::has('adnotation'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success">{{Session::get('adnotation') }}</div>
                                    </div>
                                </div>
                                @php
                                    Session::forget('adnotation');
                                @endphp
                            @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Nazwa Hotelu</label>
                                        <input type="text" id="name" class="form-control" name="name" placeholder="Tutaj wprowadź nazwę hotelu" value="{{$hotel->name}}"required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="voivode">Województwo</label>
                                        <select name="voivode" id="voivode" class="form-control" data-element="voivode" required>
                                            <option value="0">Wybierz</option>
                                            @foreach($voivodes as $voivode)
                                                <option value ="{{$voivode->id}}" @if($hotel->voivode_id == $voivode->id) selected @endif>{{$voivode->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">Miasto</label>
                                        <select name="city" id="city" class="form-control" required>
                                            @foreach($cities as $city)
                                                @if($city->id == $hotel->city_id)
                                                    <option value="{{$city->id}}" selected>{{$city->name}}</option>
                                                @else
                                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Cena za salę</label>
                                        <input type="number" step="0.01" min="0" name="price" id="price" class="form-control" placeholder="Cena w złotówkach np. 125,99" value="{{$hotel->price}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="comment">Komentarz</label>
                                        <input type="text" name="comment" id="comment" class="form-control" placeholder="Tutaj wprowadź krótki komentarz max 255 znaków" value="{{$hotel->comment}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="button-container">
                                        <input type="button" class="btn btn-danger" value="Usuń hotel" data-element="usun" style="width:100%;font-size:1.1em;font-weight:bold;margin-bottom:1em;margin-top:1em;">
                                        <button class="btn btn-info" type="button" id="redir" style="width:100%;font-size:1.1em;font-weight:bold;">Pzejdz do listy hoteli</button>
                                        <input type="submit" id="saveHotel" class="btn btn-success" value="Zapisz zmiany" style="width:100%;font-size:1.1em;font-weight:bold;margin-bottom:1em;margin-top:1em;">

                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false
        });

        function clearContent(container) {
            container.innerHTML = '';
        }

        //Walidacja Zapisu
        $('#saveHotel').on('click', function() {
            var name = $("#name").val();
            var price = $('#price').val();
            var voivode = $('#voivode').val();
            var city = $('#city').val();
            var validate = true;
            if (name.trim().length == 0) {
                swal('Wprowadź nazwę hotelu!')
                validate = false;
                return false;
            }
            if (voivode == 0) {
                swal('Wybierz województwo!')
                validate = false;
                return false;
            }
            if (city == 0 || city=='') {
                swal('Wybierz miasto!')
                validate = false;
                return false;
            }
            if (price == 0) {
                swal('Wybierz cene za salę')
                validate = false;
                return false;
            }
            if(validate){
                $('#saveHotel').prop("disabled", "disabled");
                $('#formToSubmitt').submit();
            }
        });



        document.addEventListener('DOMContentLoaded', function(event) {
           let formContainer = document.querySelector('.form-container');

           function changeEventHandler(e) {
               if(e.target.dataset.element === 'voivode') {
                   let voivodeId = e.target.value;
                   $.ajax({
                       method: "POST",
                       url: "{{ route('api.getCitiesNames') }}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                           "id": voivodeId
                       },
                       success: function(response, status) {
                         let cityInput = document.getElementById('city');
                         clearContent(cityInput);
                         let zeroValueOption = document.createElement('option');
                         zeroValueOption.value = 0;
                         zeroValueOption.textContent = "Wybierz";
                         cityInput.appendChild(zeroValueOption);

                         for(var i = 0; i < response.length; i++) {
                             let optionC = document.createElement('option');
                             optionC.value = response[i].id;
                             optionC.textContent = response[i].name;
                             cityInput.appendChild(optionC);
                         }

                       },
                       error: function(err, status, info) {
                           console.log(err);
                           console.log(status);
                           console.log(info);
                       }
                   })
               }
           }

           function clickEventHandler(e) {
               if(e.target.dataset.element === 'usun') {
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
                           let form = document.getElementById('formularz');
                           let buttonContainer = document.querySelector('.button-container');
                           let newElement = document.createElement('div');
                           newElement.innerHTML = '<input type="hidden" value="usun" name="usun">';
                           buttonContainer.appendChild(newElement);
                           $('#formToSubmitt').submit();
                       }
                   });
               }else if(e.target.id == 'redir') {
                   window.location.href = "{{URL::to('/showHotels')}}"
               }
           }

           formContainer.addEventListener('change', changeEventHandler);
           formContainer.addEventListener('click', clickEventHandler);
        });
    </script>
@endsection
