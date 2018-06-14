@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Podsumowanie oddziałów - Raport Dzienny</div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageWeekReportDepartmentsSummary') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Miesiąc:</label>
                    <select class="form-control" name="month_selected">
                        @foreach($months as $key => $value)
                            <option @if($month == $key) selected @endif value="{{$key}}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input style="margin-top: 25px; width: 100%" type="submit" class="btn btn-info" value="Generuj raport">
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info"> <strong>Podsumowanie oddziałów - Raport Dzienny - zestawienie statystyk dotyczących wszystkich oddziałów w wybranym miesiącu, podzielonych na tygodnie z rozbiciem na poszczególne dni. </strong></br>
                <div class="additional_info">
                    RBH = Roboczogodziny (godziny w tygodniu / godziny weekendowe) - TOTAL (Średnia RBH w tygodniu)</br>
                    Średnia = Zgody / RBH </br>
                    % celu = 100% * Zgody / Cel zgód </br>
                    % janków = 100% * liczba janków / liczba odsłuchanych rozmów </br>
                    Czas Rozmów = 100% * Czas rozmów / RBH
                </div>
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
                                @include('mail.weekReportDepartmentsSummary')
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
