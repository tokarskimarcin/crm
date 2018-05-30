@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Ranking Trenerów (Miesięczny)</div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageMonthReportCoachRanking') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-4">
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
            <div class="col-md-4">
                <div class="form-group">
                    <label>Miesiąc:</label>
                    <select class="form-control" name="month_selected">
                        @foreach($months as $key => $value)
                            <option @if($month == $key) selected @endif value="{{$key}}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input style="margin-top: 25px; width: 100%" type="submit" class="btn btn-info" value="Generuj raport">
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info"> <strong>Podsumowanie Trenerów - Raport Miesięczny/Tygodniowy -  zestawienie statystyk dla wszystkich trenerów z danego oddziału w wybranym miesiącu, podzielonych na tygodnie. </strong></br>
                <div class="additional_info">Średnia = liczba godzin / umówienia </br>
                    % janków = 100% * ilość janków / wszystkie sprawdzone rozmowy </br>
                    % ilość um/poł = 100% * umówienia / ilość połączeń</div>
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
                                @include('mail.monthReportCoachRanking')
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

    </script>
@endsection
