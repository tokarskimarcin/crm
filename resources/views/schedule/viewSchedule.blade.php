@extends('layouts.main')
@section('content')
<style>
    button{
        width: 100%;
        height: 50px;
    }
    td.details-control {
        background: url({{ asset('/image/details_open.png')}}) no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url({{ asset('/image/details_close.png')}}) no-repeat center center;
    }
    td{
        text-align: center;
    }
    .reason{
        width: 101px;
    }
</style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Podgląd Grafiku</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Podgląd Grafiku
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="well">
                                            <h1 style ="font-family: 'bebas_neueregular',sans-serif; margin-top:0px;text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.8); font-size:25px;">Wybierz tydzień:</h1>
                                            <form class="form-horizontal" method="post" action="view_schedule">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <select class="form-control" name="show_schedule" id="week_text" onchange="setTextField(this)" onload="setTextField(this)">
                                                    @php($date = new DateTime())
                                                    @for ($i=0; $i < 5; $i++)
                                                        @php
                                                                    $date->modify('last monday');//poniedziałek
                                                                    $data_czytelna = $date->format('Y.m.d');
                                                                    $data = $date->format("W"); // numer tygodnia
                                                                    $date->modify("next sunday"); // niedziela
                                                                    $data_czytelna2 =   $date->format('Y.m.d');
                                                                    $date->modify("+7 day");
                                                        @endphp
                                                        @if (isset($number_of_week))
                                                            @if ($data == $number_of_week)
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
                                                <input id="year" type = "hidden" name = "year" value = "" />
                                                </br>
                                                <button type="submit" class="btn btn-primary" name="show_week_grafik_send" style="font-size:18px; width:100%;">Wyszukaj</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @if (isset($number_of_week))
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <div class="panel-heading" style="border:1px solid #d3d3d3;"><h4><b>Analiza Grafik Plan</b></h4></div>
                                                <tr>
                                                    <td align="center"><b>Osoba</b></td>
                                                    <?php $week_array = ['Pon','Wt','Śr','Czw','Pt','Sob','Nie'];
                                                    $rbg_monday =0;
                                                    $rbg_tuesday =0;
                                                    $rbg_wednesday =0;
                                                    $rbg_thursday =0;
                                                    $rbg_friday =0;
                                                    $rbg_saturday =0;
                                                    $rbg_sunday =0;
                                                    ?>
                                                    @foreach($week_array as $item)
                                                        <td align="center"><b>{{$item}}</b></td>
                                                    @endforeach
                                                </tr>
                                                @foreach($schedule_user as $item =>$key)
                                                 <tr>
                                                     <td align="center"><b>{{$key->user_first_name.' - '.$key->user_last_name}}</b></td>
                                                     <td align="center"><b>{{$key->monday_start !== null ?  substr($key->monday_start,0,5).' - '.substr($key->monday_stop,0,5) : ($key->monday_comment !== null ? $key->monday_comment : null) }}</b></td>
                                                     <td align="center"><b>{{$key->tuesday_start !== null ?  substr($key->tuesday_start,0,5).' - '.substr($key->tuesday_stop,0,5) : ($key->tuesday_comment !== null ? $key->tuesday_comment : null) }}</b></td>
                                                     <td align="center"><b>{{$key->wednesday_start !== null ?  substr($key->wednesday_start,0,5).' - '.substr($key->wednesday_stop,0,5) : ($key->wednesday_comment !== null ? $key->wednesday_comment : null) }}</b></td>
                                                     <td align="center"><b>{{$key->thursday_start !== null ?  substr($key->thursday_start,0,5).' - '.substr($key->thursday_stop,0,5) : ($key->thursday_comment !== null ? $key->thursday_comment : null) }}</b></td>
                                                     <td align="center"><b>{{$key->friday_start !== null ?  substr($key->friday_start,0,5).' - '.substr($key->friday_stop,0,5) : ($key->friday_comment !== null ? $key->friday_comment : null) }}</b></td>
                                                     <td align="center"><b>{{$key->saturday_start !== null ?  substr($key->saturday_start,0,5).' - '.substr($key->saturday_stop,0,5) : ($key->saturday_comment !== null ? $key->saturday_comment : null) }}</b></td>
                                                     <td align="center"><b>{{$key->sunday_start !== null ?  substr($key->sunday_start,0,5).' - '.substr($key->sunday_stop,0,5) : ($key->sunday_comment !== null ? $key->sunday_comment : null) }}</b></td>
                                                    @php
                                                    $rbg_monday += $key->sec_monday;
                                                    $rbg_tuesday += $key->sec_tuesday;
                                                    $rbg_wednesday += $key->sec_wednesday;
                                                    $rbg_thursday += $key->sec_thursday;
                                                    $rbg_friday += $key->sec_friday;
                                                    $rbg_saturday += $key->sec_saturday;
                                                    $rbg_sunday += $key->sec_sunday;
                                                    @endphp
                                                 </tr>
                                                    <?php $lp = 8;
                                                        $number_day_of_week = 0;?>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td>RBH:{{$rbg_monday/3600}}</td>
                                                    <td>RBH:{{$rbg_tuesday/3600}}</td>
                                                    <td>RBH:{{$rbg_wednesday/3600}}</td>
                                                    <td>RBH:{{$rbg_thursday/3600}}</td>
                                                    <td>RBH:{{$rbg_friday/3600}}</td>
                                                    <td>RBH:{{$rbg_saturday/3600}}</td>
                                                    <td>RBH:{{$rbg_sunday/3600}}</td>
                                                </tr>
                                            </table>
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
    </div>

@endsection

@section('script')
<script>
    function setTextField(ddl) {
        document.getElementById('year').value = ddl.options[ddl.selectedIndex].text;
    }
    $(document).ready(function() {
        $('#year').val($("#week_text option:selected").text());
    });

</script>
@endsection
