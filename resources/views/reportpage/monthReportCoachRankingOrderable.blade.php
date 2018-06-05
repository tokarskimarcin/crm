@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Ranking Trenerów</div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageMonthReportCoachRankingOrderable') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Oddział:</label>
                        <select class="form-control" name="dep_selected">
                            <option value="0" @if($dep_id == 0) selected @endif>Wszyscy</option>
                            <option value="-1" @if($dep_id == -1) selected @endif>Badania</option>
                            <option value="-2" @if($dep_id == -2) selected @endif>Wysyłka</option>
                            @foreach($departments as $key => $value)
                                <option @if($dep_id == $value->id) selected @endif value="{{$value->id}}">{{ $value->departments->name . ' ' . $value->department_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sortuj po kolumnie:</label>
                        <select class="form-control" name="cell_selected">
                            @foreach($cellArray as $key => $value)
                                <option value="{{$key}}" @if(isset($cell_selected) && $cell_selected == $key) selected @endif > {{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="myLabel">Data początkowa:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" >
                            <input class="form-control" name="date_start" id="date" type="text" value=@if(isset($dateStart)) {{$dateStart}} @else {{date("Y-m").'-01'}} @endif>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_stop" class="myLabel">Data końcowa:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak">
                            <input class="form-control" name="date_stop" id="date_stop" type="text" value=@if(isset($dateStop)) {{$dateStop}} @else {{date("Y-m-d")}} @endif>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input style="margin-top: 25px; width: 100%" type="submit" class="btn btn-info" value="Generuj raport">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>Ranking Trenerów - Raport Miesięczny -  zestawienie statystyk dla wszystkich trenerów, w zależności od wybranej daty. </strong></br>
                <div class="additional_info">
                    Średnia = liczba godzin / umówienia </br>
                    % janków = 100% * ilość janków / wszystkie sprawdzone rozmowy </br>
                    % czas przerw/Liczba godzin = 100% * zas przerw / Liczba godzin</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="start_stop">
                            <div class="panel-body">
                                @include('mail.monthReportCoachRankingOrderable')
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

    <script>
        var todayDate = new Date(2018,5,1).getDate();
        var endD= new Date(new Date().setDate(todayDate));
        $('.form_date').datetimepicker({
            language:  'pl',
            startDate : endD,
            autoclose: 1,
            minView : 2,
            pickTime: false,
        });
    </script>
@endsection
