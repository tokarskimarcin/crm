@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Podsumowanie Trenerów - Raport Miesięczny/Tygodniowy</div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageMonthReportCoachSummary') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Oddział:</label>
                    <select class="form-control" name="dep_selected">
                        @foreach($departments as $key => $value)
                            <option @if($dep_id == $value->id) selected @endif value="{{$value->id}}">{{ $value->departments->name . ' ' . $value->department_type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Miesiąc:</label>
                    <select class="form-control" name="month_selected">
                        @foreach($months as $key => $value)
                            <option @if($month == $key) selected @endif value="{{$key}}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Nowi konsultanci ~30RBH:</label>
                    <select class="form-control" name="onlyNewUser" id="onlyNewUser">
                        <option value="0" @if(isset($onlyNewUser) && $onlyNewUser == 0) selected  @endif>Z konsultantami ~30RBH</option>
                        <option value="1" @if(isset($onlyNewUser) && $onlyNewUser == 1) selected @endif>Tylko konsultanci ~30RBH</option>
                        <option value="2" @if(isset($onlyNewUser) && $onlyNewUser == 2) selected @endif>Bez konsultantów ~30RBH</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
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
                                @include('mail.monthReportCoachSummary')
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
