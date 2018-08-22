@extends('layouts.main')
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport tygodniowy telemarketing</div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <form action="{{URL::to('/pageWeekReportTelemarketing')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label for="date" class="myLabel">Data początkowa:</label>
                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:50%;">
                        <input class="form-control" name="date_start" id="date_start" type="text" value="{{ date("Y-m-d",strtotime('-7 Days'))}}">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_stop" class="myLabel">Data końcowa:</label>
                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:50%;">
                        <input class="form-control" name="date_stop" id="date_stop" type="text" value="{{ date("Y-m-d",strtotime('-1 Days'))}}">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-info form-control" value="Generuj" style="width:50%;">
                </div>
            </form>
        </div>
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        @include('mail.weekReportTelemarketing')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('/js/moment.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });

            let dateStartInput = $('#date_start');
            let dateStopInput = $('#date_stop');
            dateStartInput.change(function () {
                dateStopInput.val(moment(dateStartInput.val()).add(6,'d').format('YYYY-MM-DD'));
            });

            dateStopInput.change(function () {
                dateStartInput.val(moment(dateStopInput.val()).subtract(6,'d').format('YYYY-MM-DD'));
            });
        });
    </script>
@endsection
