@extends('layouts.main')
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport Miesięczny Konsultanci</div>
            </div>
        </div>
    </div>
    <form method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Trener:</label>
                    <select class="form-control" id="coach_id" name="coach_id">
                        <option>Wybierz</option>
                        @foreach($coaches as $item)
                            <option @if($item->id == $coach_selected) selected @endif value="{{ $item->id }}">{{ $item->last_name . ' ' . $item->first_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Miesiąc:</label>
                    <select class="form-control" id="month_selected" name="month_selected">
                        @foreach($months as $key => $value)
                            <option @if($key == $month) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Nowi konsultanci <30RBH:</label>
                    <select class="form-control" name="onlyNewUser" id="onlyNewUser">
                        <option value="0" @if(isset($onlyNewUser) && $onlyNewUser == 0) selected  @endif>Z konsultantami <30RBH</option>
                        <option value="1" @if(isset($onlyNewUser) && $onlyNewUser == 1) selected @endif>Tylko konsultanci <30RBH</option>
                        <option value="2" @if(isset($onlyNewUser) && $onlyNewUser == 2) selected @endif>Bez konsultantów <30RBH</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="submit" class="btn btn-info" value="Generuj raport" style="width: 100%; margin-top: 24px">
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info"> <strong>Raport Miesięczny Konsultanci- zestawienie statystyk dotyczących wybranego trenera w wybranym miesiącu. </strong></br>
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
                                @isset($data)
                                    @include('mail.monthReportConsultant')
                                @else
                                    <div class="alert alert-info">
                                        Wybierz trenera
                                    </div>
                                @endif
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
