@extends('layouts.main')
@section('content')
<style>
    thead {
        background-color: #4a4e54;
        color: white;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Testy / Twoje testy</div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#waiting">Oczekujące</a></li>
    <li><a data-toggle="tab" href="#finished">Zakończone</a></li>
    <li><a data-toggle="tab" href="#judged">Ocenione</a></li>
</ul>

<div class="tab-content">
    <div id="waiting" class="tab-pane fade in active">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th style="width: 5%">Lp.</th>
                        <th style="width: 15%">Data</th>
                        <th style="width: 20%">Osoba testująca</th>
                        <th>Tytuł testu</th>
                        <th style="width: 10%">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($tests->where('status', '=', 2) as $test)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$test->updated_at}}</td>
                            <td>{{$test->cadre->first_name . ' ' . $test->cadre->last_name}}</td>
                            <td>{{$test->name}}</td>
                            <td>
                                <a class="btn btn-info" href="{{ URL::to('/test_user') }}/{{$test->id}}">
                                    <span class="glyphicon glyphicon-pencil" style="color: white"></span> Przystąp do testu
                                </a>    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer">Brak elementów w tej kategorii!</div>
            @endif
        </div>
    </div>
    <div id="finished" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th style="width: 5%">Lp.</th>
                        <th style="width: 15%">Data</th>
                        <th style="width: 20%">Osoba testująca</th>
                        <th>Tytuł testu</th>
                        <th style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($tests->where('status', '=', 3) as $test)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$test->updated_at}}</td>
                            <td>{{$test->cadre->first_name . ' ' . $test->cadre->last_name}}</td>
                            <td>{{$test->name}}</td>
                            <td>Czeka na ocenę</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer">Brak elementów w tej kategorii!</div>
            @endif
        </div>
    </div>
    <div id="judged" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th style="width: 5%">Lp.</th>
                        <th style="width: 10%">Data</th>
                        <th style="width: 10%">Osoba testująca</th>
                        <th style="width: 10%">Osoba sprawdzająca</th>
                        <th>Tytuł testu</th>
                        <th style="width: 10%">Rezultat</th>
                        <th style="width: 10%">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($tests->where('status', '=', 4) as $test)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$test->updated_at}}</td>
                            <td>{{$test->cadre->first_name . ' ' . $test->cadre->last_name}}</td>
                            <td>{{$test->checkedBy->first_name . ' ' . $test->checkedBy->last_name}}</td>
                            <td>{{$test->name}}</td>
                            <td>
                                {{$test->result}} / {{$test->questions->count()}}
                            </td>
                            <td>
                                <a class="btn btn-default" href="{{ URL::to('/test_result') }}/{{$test->id}}">
                                    <span class="glyphicon glyphicon-pencil" style="color:green"></span> Szczegóły
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer">Brak elementów w tej kategorii!</div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')
<script>

</script>
@endsection
