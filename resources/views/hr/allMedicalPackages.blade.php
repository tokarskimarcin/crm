@extends('layouts.main')
@section('content')

<style>
    .myLabel {
        font-size: 20px;
        color: #aaa;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Rozliczenia / Pakiety medyczne</div>
        </div>
    </div>
</div>
<form method="POST" action="{{ URL::to('/medical_packages_all') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        {{--<div class="col-md-3">--}}
            {{--<div class="form-group">--}}
                {{--<select class="form-control" name="medical_type">--}}
                    {{--<option value="0">Wszystkie</option>--}}
                    {{--<option value="1">Nowe</option>--}}
                    {{--<option value="2">Usunięte</option>--}}
                    {{--<option value="3">Edycja danych</option>--}}
                {{--</select>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="col-md-4">
            <div class="form-group">
                <select class="form-control" name="medical_year">
                    <option value="2018">2018</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <select class="form-control" name="medical_month">
                    @foreach($months as $item)
                        <option @if($selected_month == $item['id']) selected @endif value="{{$item['id']}}">{{$item['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <input type="submit" style="width: 100%" class="btn btn-info" value="Wybierz"/>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-3">
        <div class="col-md-12">
            <label class="myLabel">Bez zmian:</label>
        </div>
        <div class="col-md-12">
            <div class="alert" style="background-color: #6dedf9"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="col-md-12">
            <label class="myLabel">Nowe:</label>
        </div>
        <div class="col-md-12">
            <div class="alert" style="background-color: #81f96d"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="col-md-12">
            <label class="myLabel">Zmiany:</label>
        </div>
        <div class="col-md-12">
            <div class="alert" style="background-color: #edf96d"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="col-md-12">
            <label class="myLabel">Usunięte:</label>
        </div>
        <div class="col-md-12">
            <div class="alert" style="background-color: #f96d6d"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <input type="button" onclick="tableToExcel('tabelka', 'Raport za miesiąc {{$selected_month}}')" value="Export table to Excel" class="btn btn-info" style="width:100%;margin-bottom:1em;">
    </div>
</div>
<div class="row selectingClass">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <b>Aktywne pakiety medyczne</b>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped thead-inverse">
                        <thead>
                        <tr>
                            <th>Nazwisko</th>
                            <th>Imie</th>
                            <th>PESEL</th>
                            <th>Data ur.</th>
                            <th>Kod pocztowy</th>
                            <th>Miejscowość</th>
                            <th>Ulica</th>
                            <th>Nr domu</th>
                            <th>Nr mieszkania</th>
                            <th>Pakiet</th>
                            <th>Wariant</th>
                            <th>Zakres</th>
                            <th>Tel.</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($packages as $item)
                            <tr style="background-color:
                            @if($item->flag == 1)
                                    #edf96d
                            @elseif($item->flag == 2)
                                    #81f96d
                            @elseif($item->flag == 3)
                                    #f96d6d
                            @else
                                    #6dedf9
                            @endif
                                    ;">
                                <td>{{$item->user_last_name}}</td>
                                <td>{{$item->user_first_name}}</td>
                                <td>{{$item->pesel}}</td>
                                <td>{{$item->birth_date}}</td>
                                <td>{{$item->postal_code}}</td>
                                <td>{{$item->city}}</td>
                                <td>{{$item->street}}</td>
                                <td>{{$item->house_number}}</td>
                                <td>{{$item->flat_number}}</td>
                                <td>{{$item->package_name}}</td>
                                <td>{{$item->package_variable}}</td>
                                <td>{{$item->package_scope}}</td>
                                <td>{{$item->phone_number}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


{{--Table id=tabelka is formated for downloading puroposes, don't delete it. --}}

<table class="table table-bordered table-striped thead-inverse" id="tabelka" style="display:none;">
        <tr>
            <th>Nazwisko</th>
            <th>Imie</th>
            <th>PESEL</th>
            <th>Data ur.</th>
            <th>Kod pocztowy</th>
            <th>Miejscowość</th>
            <th>Ulica</th>
            <th>Nr domu</th>
            <th>Nr mieszkania</th>
            <th>Pakiet</th>
            <th>Wariant</th>
            <th>Zakres</th>
            <th>Tel.</th>
        </tr>
        @foreach($packages as $item)
            <tr style="background-color:
                @if($item->flag == 1)
                    #edf96d
                @elseif($item->flag == 2)
                    #81f96d
                @elseif($item->flag == 3)
                    #f96d6d
                @else
                    #6dedf9
                @endif
            ;">
                <td>{{$item->user_last_name}}</td>
                <td>{{$item->user_first_name}}</td>
                <td>{{$item->pesel}}</td>
                <td>{{$item->birth_date}}</td>
                <td>{{$item->postal_code}}</td>
                <td>{{$item->city}}</td>
                <td>{{$item->street}}</td>
                <td>{{$item->house_number}}</td>
                <td>{{$item->flat_number}}</td>
                <td>{{$item->package_name}}</td>
                <td>{{$item->package_variable}}</td>
                <td>{{$item->package_scope}}</td>
                <td>{{$item->phone_number}}</td>
            </tr>
        @endforeach
</table>

@endsection
@section('script')
    <script type="text/javascript">
        var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
            return function(table, name) {
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                window.location.href = uri + base64(format(template, ctx))
            }
        })()
        /**
         * Code used from : https://stackoverflow.com/questions/22317951/export-html-table-data-to-excel-using-javascript-jquery-is-not-working-properl
         */
    </script>
@endsection
