@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Pomoc / Ocena pracowników IT</div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#today">Dzisiaj</a></li>
  <li><a data-toggle="tab" href="#this_week">Ostatni tydzień</a></li>
  <li><a data-toggle="tab" href="#this_month">Ostatni miesiąc</a></li>
  <li><a data-toggle="tab" href="#total">Suma</a></li>
</ul>

<div class="tab-content" style="padding-top: 25px">

    <div id="today" class="tab-pane fade in active">
        <div class="panel panel-default">
            <div class="panel-heading">
                Dzisiaj
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover thead-inverse">
                                <thead>
                                    <tr>
                                        <th>Pracownik</th>
                                        <th>Liczba ocen pozytywnych</th>
                                        <th>Ocena jakości wykonania</th>
                                        <th>Ocena kontaktu z serwisantem</th>
                                        <th>Ocena czasu wykonania</th>
                                        <th>Ocena ogólna</th>
                                        <th>Średni czas realizacji</th>
                                        <th>Oddzwonienia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results_today as $result)
                                        <tr class='clickable-row' data-href='{{URL::to('/it_worker/')}}/{{$result->user_id}}' title="Pokaż profil pracownika">
                                            <td>{{$result->first_name . ' ' . $result->last_name}}</td>
                                            <td>{{$result->user_sum_repaired}}/{{$result->user_sum}}</td>
                                            <td>{{round($result->user_quality, 2)}}/6</td>
                                            <td>{{round($result->user_contact, 2)}}/6</td>
                                            <td>{{round($result->user_time, 2)}}/6</td>
                                            <td>{{round($result->user_judge_sum, 2)}}/6</td>
                                            <td>{{round($result->notifications_time_sum, 2)}} h</td>
                                            <td>{{$result->response_after}}/{{$result->user_sum}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="this_week" class="tab-pane fade">
        <div class="panel panel-default">
            <div class="panel-heading">
                Ostatni tydzień
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover thead-inverse">
                                <thead>
                                    <tr>
                                        <th>Pracownik</th>
                                        <th>Liczba ocen pozytywnych</th>
                                        <th>Ocena jakości wykonania</th>
                                        <th>Ocena kontaktu z serwisantem</th>
                                        <th>Ocena czasu wykonania</th>
                                        <th>Ocena ogólna</th>
                                        <th>Średni czas realizacji</th>
                                        <th>Oddzwonienia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results_week as $result)
                                        <tr class='clickable-row' data-href='{{URL::to('/it_worker/')}}/{{$result->user_id}}' title="Pokaż profil pracownika">
                                            <td>{{$result->first_name . ' ' . $result->last_name}}</td>
                                            <td>{{$result->user_sum_repaired}}/{{$result->user_sum}}</td>
                                            <td>{{round($result->user_quality, 2)}}/6</td>
                                            <td>{{round($result->user_contact, 2)}}/6</td>
                                            <td>{{round($result->user_time, 2)}}/6</td>
                                            <td>{{round($result->user_judge_sum, 2)}}/6</td>
                                            <td>{{round($result->notifications_time_sum, 2)}} h</td>
                                            <td>{{$result->response_after}}/{{$result->user_sum}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="this_month" class="tab-pane fade">
        <div class="panel panel-default">
            <div class="panel-heading">
                Ostatni miesiąc
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover thead-inverse">
                                <thead>
                                    <tr>
                                        <th>Pracownik</th>
                                        <th>Liczba ocen pozytywnych</th>
                                        <th>Ocena jakości wykonania</th>
                                        <th>Ocena kontaktu z serwisantem</th>
                                        <th>Ocena czasu wykonania</th>
                                        <th>Ocena ogólna</th>
                                        <th>Średni czas realizacji</th>
                                        <th>Oddzwonienia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results_month as $result)
                                        <tr class='clickable-row' data-href='{{URL::to('/it_worker/')}}/{{$result->user_id}}' title="Pokaż profil pracownika">
                                            <td>{{$result->first_name . ' ' . $result->last_name}}</td>
                                            <td>{{$result->user_sum_repaired}}/{{$result->user_sum}}</td>
                                            <td>{{round($result->user_quality, 2)}}/6</td>
                                            <td>{{round($result->user_contact, 2)}}/6</td>
                                            <td>{{round($result->user_time, 2)}}/6</td>
                                            <td>{{round($result->user_judge_sum, 2)}}/6</td>
                                            <td>{{round($result->notifications_time_sum, 2)}} h</td>
                                            <td>{{$result->response_after}}/{{$result->user_sum}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div id="total" class="tab-pane fade">
        <div class="panel panel-default">
            <div class="panel-heading">
                Suma
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover thead-inverse">
                                <thead>
                                    <tr>
                                        <th>Pracownik</th>
                                        <th>Liczba ocen pozytywnych</th>
                                        <th>Ocena jakości wykonania</th>
                                        <th>Ocena kontaktu z serwisantem</th>
                                        <th>Ocena czasu wykonania</th>
                                        <th>Ocena ogólna</th>
                                        <th>Średni czas realizacji</th>
                                        <th>Oddzwonienia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results_total as $result)
                                        <tr class='clickable-row' data-href='{{URL::to('/it_worker/')}}/{{$result->user_id}}' title="Pokaż profil pracownika">
                                            <td>{{$result->first_name . ' ' . $result->last_name}}</td>
                                            <td>{{$result->user_sum_repaired}}/{{$result->user_sum}}</td>
                                            <td>{{round($result->user_quality, 2)}}/6</td>
                                            <td>{{round($result->user_contact, 2)}}/6</td>
                                            <td>{{round($result->user_time, 2)}}/6</td>
                                            <td>{{round($result->user_judge_sum, 2)}}/6</td>
                                            <td>{{round($result->notifications_time_sum, 2)}} h</td>
                                            <td>{{$result->response_after}}/{{$result->user_sum}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
$(".clickable-row").click(function() {
    window.location = $(this).data("href");
});
</script>
@endsection
