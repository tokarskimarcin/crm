@extends('layouts.main')
@section('content')

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport Dzienny {{$newUsersRbh}} RBH</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info">
                <strong>Raport Dzienny {{$newUsersRbh}} RBH</strong> pokazuje informacje o konsultantach, którzy przepracowali <u> mniej niż {{$newUsersRbh}} RBH</u>, oraz tych, którzy <u>zaczeli pracę w danym miesiącu</u> i już przekroczyli {{$newUsersRbh}}RBH.
                Osoby, które przekroczyły {{$newUsersRbh}}RBH będą widoczne w raporcie  <i>do końca bierzącego miesiąca</i>  a ich wyniki zostały policzone za okres pierwszych {{$newUsersRbh}}RBH.
                Jeśli w kolumnie <i>liczba godzin</i>, znajdują się wartości oddzielone ukośnikiem, oznacza to, że ilość godzin z lewej strony
                została wykorzystana do wyliczenia statystyk (Około pierwsze {{$newUsersRbh}} godzin pracy konsultanta), a wartość po prawej, oznacza ile do dnia dzisiejszego przepracował RBH.
                Konsultanci są pogrupowani oddziałami.
            </div>
        </div>
        <div class="col-lg-12">
            <form action="{{URL::to('/pageDayReportNewUsers')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label class="myLabel">Data:</label>
                    <select class="form-control" name="month_selected" id="month_selected">
                        @foreach($sMonths as $key => $month)
                            <option @if($Smonth_selected == $key) selected @endif value="{{ $key }}">{{ $month }}</option>
                        @endforeach
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
                                    @include('mail.statisticsRBHMail.dayReportNewUsers');
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
    $('.form_date').datetimepicker({
        language:  'pl',
        autoclose: 1,
        minView : 1,
        pickTime: false,
    });
</script>
@endsection
