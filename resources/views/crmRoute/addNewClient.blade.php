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
            <div class="alert gray-nav ">Nowy Klient</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="heading-container">
                                Dodaj nowego klienta
                            </div>
                        </div>
                    </div>
                    <div class="form-container">
                        <form action="{{URL::to('/addNewClient')}}" method="POST">
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
                                        <label for="name">Nazwa Klienta</label>
                                        <input type="text" id="name" class="form-control" name="name" placeholder="Tutaj wprowadź nazwę klienta" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="button-container">
                                        <input type="submit" class="btn btn-success" value="Zapisz" style="width:100%;font-size:1.1em;font-weight:bold;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-info" type="button" id="redir" style="width:100%;margin-top:1em;margin-bottom:1em;">Przejdź do listy klientów</button>
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

        document.addEventListener('DOMContentLoaded', function(event) {
           let formContainer = document.querySelector('.form-container');

           function clickEventHandler(e) {
               if(e.target.id == 'redir') {
                   window.location.href = "{{URL::to('/showHotels')}}"
               }
           }

           formContainer.addEventListener('click', clickEventHandler)
        });
    </script>
@endsection
