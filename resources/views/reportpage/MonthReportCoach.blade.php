@extends('layouts.main')
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Miesięczny Raport Trenerzy</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ URL::to('/pageMonthReportCoach') }}" id="checkCoach">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Trener:</label>
                        <select class="form-control" name="coach_id" id="coach_id">
                            <option value="0">Wybierz</option>
                            @foreach($coaches as $coach)
                                <option @if(isset($leader) && ($leader->id == $coach->id)) selected @endif value="{{ $coach->id }}">{{ $coach->last_name . ' ' . $coach->first_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Miesiąc:</label>
                        <select class="form-control" name="month_selected" id="month_selected">
                            @foreach($months as $key => $month)
                                <option @if($month_selected == $key) selected @endif value="{{ $key }}">{{ $month }}</option>
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
                    <input type="submit" class="btn btn-info" value="Pokaż statystyki" style="width: 100%; margin-top: 25px" id="select_coach">
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info"> <strong>Raport Miesięczny - zestawienie statystyk dotyczących wybranego trenera w wybranym miesiącu, podzielonych na poszczególne tygodnie. </strong></br>
                <div class="additional_info">Średnia = liczba godzin / umówienia </br>
                    % janków = 100% * ilość janków / wszystkie sprawdzone rozmowy </br>
                    % ilość um/poł = 100% * umówienia / ilość połączeń</div>
            </div>
        </div>
    </div>

    @if(isset($coachData))
        @if($coachData->count() > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="start_stop">
                                    <div class="panel-body">
                                        @include('mail.monthReportCoach')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        Brak statystyk dla danego trenera!
                    </div>
                </div>
            </div>
        @endif

    @endif

    </div>

@endsection

@section('script')
    <script>
        $(document).ready(() => {
            $('#select_coach').click((e) => {
                e.preventDefault();
                var coach_id = $('#coach_id').val();
                if (coach_id == 0) {
                    swal('Wybierz trenera!');
                    return false;
                }
                $('#checkCoach').submit();
            });
        });
    </script>
@endsection
