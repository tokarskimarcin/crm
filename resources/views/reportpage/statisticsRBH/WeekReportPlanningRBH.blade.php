@extends('layouts.main')
@section('content')

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Tygodniowy Raport (Planowanie)</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form action="{{URL::to('/weekReportPlanningRBH')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label class="myLabel">Zakres:</label>
                    <select class="form-control" name="date" id="date" onchange="setTextField(this)">
                        @php $date = new DateTime();  @endphp
                        @for ($i=0; $i < 5; $i++)
                            @php
                                $date->modify('last monday');//poniedziałek
                                $data_czytelna = $date->format('Y.m.d');
                                $data = $date->format("W"); // numer tygodnia
                                $date->modify("next sunday"); // niedziela
                                $data_czytelna2 =   $date->format('Y.m.d');
                                $date->modify("+7 day");
                            @endphp
                            @if (isset($SactualWeekNumber))
                                @if ($data == $SactualWeekNumber)
                                    <option value={{$data}} selected>{{$data_czytelna.' -> '.$data_czytelna2}}</option>;
                                @else
                                    <option value={{$data}}>{{$data_czytelna.' -> '.$data_czytelna2}}</option>;
                                @endif
                            @else
                                @if ($data == date("W"))
                                    <option value={{$data}} selected>{{$data_czytelna.' -> '.$data_czytelna2}}</option>;
                                @else
                                    <option value={{$data}}>{{$data_czytelna.' -> '.$data_czytelna2}}</option>;
                                @endif
                            @endif
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-info form-control" value="Generuj" style="width:50%;">
                </div>

            </form>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        @include('mail.statisticsRBHMail.weekReportPlanningRBH')
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
