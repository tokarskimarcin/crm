@extends('layouts.main')
@section('content')
<style>
    button{
        width: 100%;
        height: 50px;
    }
</style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Podgląd Wypłat</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Podgląd Wypłat
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        <div class="well">
                                            <form action="view_payment" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-8">
                                                    <select name="search_money_month" class="form-control" style="font-size:18px;">
                                                        @for ($i=0; $i < 3; $i++)
                                                            @php
                                                            $date = date("Y-m", mktime(0, 0, 0, date("m")-$i, 1, date("Y")));
                                                            @endphp
                                                            @if (isset($month))
                                                                @if ($month == $date)
                                                                    <option selected>{{$date}}</option>
                                                                @else{
                                                                    <option>{{$date}}</option>
                                                                @endif
                                                            @else
                                                                <option>{{$date}}</option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary" id="show_load_data_info" style="width:100%;">Wyświetl</button>
                                                </div></br></br>
                                            </form>
                                        </div>
                                    </div>

                                <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div style="float:left;">
                                                        <h4 style="margin-top:20px;"><b>Tabela Wypłat - Umowy Szkoleniowe:</b></h4>
                                                        </div><br><br><br>
                                                @if(isset($month))
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>Lp.</th>
                                                            <th>Nazwisko</th>
                                                            <th>Imię</th>
                                                            <th>Stawka</th>
                                                            <th>Średnia</th>
                                                            <th>RBH</th>
                                                            <th>%Janków</th>
                                                            <th>Kara Janki</th>
                                                            <th>Podstawa</th>
                                                            <th>Premia</th>
                                                            <th>Prowizja</th>
                                                            <th>Stu.</th>
                                                            <th>Dok.</th>
                                                            <th>Wynagrodzenie</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('workhours.registerHour');
@endsection

@section('script')

<script>


</script>
@endsection
