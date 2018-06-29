{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows list of available campaigns,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: showHotelsAjax, showHotelsGet--}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    {{--<link rel="stylesheet" href="{{asset('/css/ScrollTabla.css')}}">--}}
@endsection
@section('content')

    <style>
        #parent {
            height: 500px;
        }

        #fixTable {
            width: 1800px !important;
        }

    </style>

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Statystyki miast</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <div class="alert alert-info" style="font-size: 1.3em;">
                        Kolorem <span style="background: #c67979;">Bordowym</span> zostały oznaczone wiersze sumujące wartości dla każdego dnia </br>
                        Kolorem <span style="background: #e6eff4;">jasno niebieskim</span> zostały oznaczone wiersze sumujące wartości z całęgo tygodnia dla poszególnego klienta </br>
                        Kolorem <span style="background: #efef7f;">Zółtym</span> zostały oznaczone komórki, które sumują wartości dla całego tygodnia dla wszystkich klientów.
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="heading-container">
                                Statystyki miast
                            </div>
                        </div>
                    </div>

                    <div id="parent">
                        <table id="fixTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th colspan="2">Tydzień/ zakres dat</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">Data</td>
                                    @foreach($days as $item)
                                    <td style="font-weight:bold;">{{$item->date}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td colspan="2" class="holdDoor">Dzień</td>
                                    @foreach($days as $item)
                                        <td>{{$item->name}}</td>
                                    @endforeach
                                    @php
                                        $i = 0;
                                        $rowspanWysylka = 1;
                                        foreach($clients['Wysyłka'] as $item) {
                                            $rowspanWysylka++;
                                        }

                                        $rowspanBadania = 1;
                                        foreach($clients['Badania'] as $item) {
                                            $rowspanBadania++;
                                        }
                                    @endphp
                                </tr>
                                    @foreach($clients['Wysyłka'] as $item)
                                        <tr>
                                            @if($i == 0)
                                                <td rowspan="{{$rowspanWysylka}}" style="vertical-align : middle;text-align:center; font-weight:bold;">Kamery</td>
                                            @endif
                                            @php
                                                $i++;
                                            @endphp
                                            <td style="font-weight:bold;">{{$item->name}}</td>
                                            @foreach($allInfo['Wysyłka'][$item->name] as $info)
                                                @if($info->type == 0)
                                                    <td>{{$info->amount}}</td>
                                                @else
                                                    <td class="sum" data-info="wysylka" data-week="{{$info->week}}" style="background: #c67979;">{{$info->amount}}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td style="font-weight:bold;">SUMA DZIEŃ WYSYŁKA</td>
                                        @foreach($allInfo['Wysyłka']['daySum'] as $info)
                                                <td  style="background: #e6eff4;">{{$info->daySum}}</td>
                                        @endforeach
                                    </tr>
                                <tr>
                                    <td style="border-right: none;"></td><td></td>
                                </tr>
                                    @php
                                        $i = 0;
                                    @endphp
                                @foreach($clients['Badania'] as $item)
                                    <tr>
                                        @if($i == 0)
                                            <td rowspan="{{$rowspanBadania}}" style="vertical-align : middle;text-align:center; font-weight:bold;">Badania</td>
                                        @endif
                                        @php
                                            $i++;
                                        @endphp
                                        <td style="font-weight:bold;">{{$item->name}}</td>
                                        @foreach($allInfo['Badania'][$item->name] as $info)
                                            @if($info->type == 0)
                                                <td>{{$info->amount}}</td>
                                            @else
                                                <td class="sum" data-info="badania" data-week="{{$info->week}}"  style="background: #c67979;">{{$info->amount}}</td>
                                            @endif

                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr>

                                    <td style="font-weight:bold;">SUMA DZIEŃ BADANIA</td>
                                    @foreach($allInfo['Badania']['daySum'] as $info)
                                        <td  style="background: #e6eff4;">{{$info->daySum}}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}





@endsection

@section('script')
    <script src="{{asset('/js/tableHeadFixer.js')}}"></script>

    <script>
        $(document).ready(function() {
            /********** GLOBAL VARIABLES ***********/
                let weeksArray = [];
                const typeArray = ['badania', 'wysylka'];

            /*******END OF GLOBAL VARIABLES*********/


            /**
             * This function fill weeksArray with weeks Number;
             */
            (function fillWeeksArray() {
                @foreach($clients['Wysyłka'] as $item)
                    @foreach($allInfo['Wysyłka'][$item->name] as $info)
                        @if($info->type == 1)
                            var flag = false;
                            var weekNumber = {{$info->week}};
                            weeksArray.forEach(item => {
                                if(item == weekNumber) {
                                    flag = true;
                                }
                            });
                            if(flag == false) {
                                weeksArray.push(weekNumber);
                            }

                        @endif
                    @endforeach
                @endforeach
            })();

            (function createSums() {
                let weekSum = 0;
                typeArray.forEach(type => {
                   weeksArray.forEach(week => {
                      const firstSumElement = document.querySelectorAll('[data-week="' + week + '"][data-info="' + type + '"]');
                      firstSumElement.forEach(element => {
                         let stringNumber = element.innerText;
                         let intStringNumber = stringNumber * 1;
                         weekSum += intStringNumber;
                      });

                      let lastSumElement = firstSumElement[firstSumElement.length - 1];
                      let cellIndex = lastSumElement.cellIndex;
                      let sumElement = lastSumElement.parentElement.nextElementSibling.children[cellIndex];
                      sumElement.textContent = weekSum;
                      sumElement.style.background = '#efef7f';

                      weekSum = 0;
                   });
                });
            })();

            $("#fixTable").tableHeadFixer({"head" : false, "left" : 2});
        });
    </script>
@endsection
