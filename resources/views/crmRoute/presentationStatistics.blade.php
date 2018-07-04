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


    @php
        $badaniaFlag = false;
        $wysylkaFlag = false;
    @endphp
{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Statystyki prezentacji</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Statystyki prezentacji
                </div>
                <div class="panel-body">
                    <div class="alert alert-info" style="font-size: 1.3em;">
                        Kolorem <span style="background: #e6eff4;">jasno niebieskim</span> zostały oznaczone wiersze sumujące wartości dla każdego dnia </br>
                        Kolorem <span style="background: #c67979;">Bordowym</span> zostały oznaczone wiersze sumujące wartości z całęgo tygodnia dla poszególnego klienta </br>
                        Kolorem <span style="background: #efef7f;">Zółtym</span> zostały oznaczone komórki, które sumują wartości dla całego tygodnia dla wszystkich klientów.
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="heading-container">
                            </div>
                        </div>
                    </div>
                    <form action="{{URL::to('/presentationStatistics')}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year">Rok</label>
                                <select id="year" class="form-control" name="year">
                                    <option value="%">Wszystkie</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="month">Miesiąc</label>
                                <select id="month" class="form-control" name="month">
                                    <option value="%">Wszystkie</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <input type="submit" class="btn btn-info" style="width:100%; margin:1em; margin-top:0;" value="Generuj"></input>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </form>
                    <div id="parent">
                        @if((isset($clients['Wysyłka']) && isset($allInfo['Wysyłka']['daySum']) && isset($allInfo['Wysyłka'])) || (isset($clients['Badania']) && isset($allInfo['Badania']) && isset($allInfo['Badania']['daySum'])))
                        <table id="fixTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th colspan="2">Tydzień/ zakres dat</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(isset($days))
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
                            @endif
                            @if(isset($clients['Wysyłka']) && isset($allInfo['Wysyłka']['daySum']) && isset($allInfo['Wysyłka']))

                                    @php
                                        $wysylkaFlag = true;
                                        $i = 0;
                                        $rowspanWysylka = 1;
                                        if(isset($clients['Wysyłka'])) {
                                            foreach($clients['Wysyłka'] as $item) {
                                                $rowspanWysylka++;
                                            }
                                        }
                                    @endphp
                                </tr>

                                        @foreach($clients['Wysyłka'] as $item)
                                            <tr>
                                                @if($i == 0)
                                                    <td rowspan="{{$rowspanWysylka}}" style="vertical-align : middle;text-align:center; font-weight:bold;"  data-specialrow="1">Kamery</td>
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
                            @endif

                            @if(isset($clients['Badania']) && isset($allInfo['Badania']) && isset($allInfo['Badania']['daySum']))
                                @php
                                    $badaniaFlag = true;
                                    $rowspanBadania = 1;
                                            if(isset($clients['Badania'])) {
                                                foreach($clients['Badania'] as $item) {
                                                    $rowspanBadania++;
                                                }
                                            }

                                @endphp
                                <tr>
                                    <td style="border-right: none;"></td><td></td>
                                </tr>
                                    @php
                                        $i = 0;
                                    @endphp

                                    @foreach($clients['Badania'] as $item)
                                        <tr>
                                            @if($i == 0)
                                                <td rowspan="{{$rowspanBadania}}" style="vertical-align : middle;text-align:center; font-weight:bold;" data-specialrow="1">Badania</td>
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
                            @endif

                            </tbody>
                        </table>
                        @else
                            <div class="alert alert-info">Brak danych</div>
                        @endif
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
                @if($badaniaFlag == true && $wysylkaFlag == true)
                    const typeArray = ['badania', 'wysylka'];
                @elseif($badaniaFlag == true)
                    const typeArray = ['badania'];
                @elseif($wysylkaFlag == true)
                    const typeArray = ['wysylka'];
                @else
                    const typeArray = [];
                @endif

            /*******END OF GLOBAL VARIABLES*********/


            /**
             * This function fill weeksArray with weeks Number;
             */

            (function fillWeeksArray() {
                @if(isset($clients['Wysyłka']))
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
                @else
                @if(isset($clients['Badania']))
                    @foreach($clients['Badania'] as $item)
                        @foreach($allInfo['Badania'][$item->name] as $info)
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
                @endif
                @endif
            })();

            (function createSums() {
                let weekSum = 0;
                typeArray.forEach(type => {
                   weeksArray.forEach(week => {
                      const firstSumElement = document.querySelectorAll('[data-week="' + week + '"][data-info="' + type + '"]'); //all sum
                      firstSumElement.forEach(element => {
                         let stringNumber = element.innerText;
                         let intStringNumber = stringNumber * 1;
                         weekSum += intStringNumber;
                      });

                       let cellIndex = null;

                       let lastSumElement = firstSumElement[firstSumElement.length - 1];
                       let firstRowToCheck = lastSumElement.parentElement.children[0];
                       if(firstRowToCheck.dataset.specialrow) {
                           cellIndex = lastSumElement.cellIndex - 1;
                       }
                       else {
                           cellIndex = lastSumElement.cellIndex;
                       }

                      let sumElement = lastSumElement.parentElement.nextElementSibling.children[cellIndex];
                      sumElement.textContent = weekSum;
                      sumElement.style.background = '#efef7f';

                      weekSum = 0;
                   });
                });
            })();

            /**
             * This function appends week numbers to month select element and years to year select element
             * IIFE function, execute after page is loaded automaticaly
             */
            (function appendMonthsAndYears() {
                const monthSelect = document.querySelector('#month');
                const yearSelect = document.querySelector('#year');
                const baseYear = '2017';
                const currentYear = {{$currentYear}};
                const currentMonth = {{$currentMonth}};

                for(let j = baseYear; j <= currentYear + 1; j++) {
                    const opt = document.createElement('option');
                    opt.value = j;
                    opt.textContent = j;
                    if(j == currentYear) {
                        opt.setAttribute('selected', 'selected');
                        selectedYear = [j];
                    }
                    yearSelect.appendChild(opt);
                }

                for(let i = 1; i < 13; i++) {
                    const opt = document.createElement('option');
                    opt.value = i;
                    opt.textContent = i;
                    if(i == currentMonth) {
                        opt.setAttribute('selected', 'selected');
                        selectedMonth = [i];
                    }
                    monthSelect.appendChild(opt);
                }
            })();

            $("#fixTable").tableHeadFixer({"head" : false, "left" : 2});
        });
    </script>
@endsection
