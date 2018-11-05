@extends('layouts.main')
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport Miesięczny 30 Rbh(zbiorczy)</div>
            </div>
        </div>
    </div>
    <form action="{{URL::to('/pageMonth30RbhReport')}}" method="post">

    <div class="row">
        <div class="col-lg-6">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="date" class="myLabel">Data początkowa:</label>
                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                    <input class="form-control" name="date_start" id="date" type="text" value="{{date("Y-m-d")}}">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label for="date_stop" class="myLabel">Data końcowa:</label>
                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                    <input class="form-control" name="date_stop" id="date_stop" type="text" value="{{date("Y-m-d")}}">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <input type="submit" class="btn btn-info form-control" value="Generuj" style="width:50%;">
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-body" style="font-size: 12px;">
                @include('mail.month30RbhReport')
            </div>
        </div>
    </div>
    </form>

@endsection

@section('script')

    <script>
        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false,
        });

        let date_start = `<?php echo $date_start; ?>`;
        let date_stop = `<?php echo $date_stop; ?>`;

        let date_start_input = document.querySelector('#date');
        $(date_start_input).val(date_start);

        let date_stop_input = document.querySelector('#date_stop');
        $(date_stop_input).val(date_stop);
    </script>
@endsection
