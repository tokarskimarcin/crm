@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Tworzenie Tras</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Tworzenie Tras
                </div>
                <div class="panel-body">
                    @include('crmRoute.client')
                    <div class="row">
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

        $(document).ready(function() {

            let mainContainer = document.querySelector('.routes-wrapper'); //zaznaczamy główny container
            function clear_modal() {
                // document.getElementsByName('client_name')[0]
                // document.getElementsByName('client_phone')[0].value ='';
                // document.getElementsByName('client_type')[0].value ='Wybierz';
                // console.log(document.getElementsByName('client_name')[0]);
            }

            function edit_client(e) {
                var client_id = e.getAttribute('data-id');
                var tr_line = e.closest('tr');
                var tr_line_name = tr_line.getElementsByClassName('client_name')[0].textContent;
                var tr_line_phone = tr_line.getElementsByClassName('client_phone')[0].textContent;
                var tr_line_type = tr_line.getElementsByClassName('client_type')[0].textContent;
                clear_modal();
                $('#Modal_Client').modal('show');
                console.log(tr_line);
            }

            function save_client(e) {
                alert('Klient dodany');
                $('#Modal_Client').modal('hide');
            }
        });
    </script>
@endsection
