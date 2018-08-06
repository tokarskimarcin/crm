@extends('layouts.main')
@section('content')

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport Dzienny 30 RBH</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form action="{{URL::to('/pageDayReport30RBH')}}" method="post">
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
                                    @include('mail.statisticsRBHMail.dayReport30RBH');
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
