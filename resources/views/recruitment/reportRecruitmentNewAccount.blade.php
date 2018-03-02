@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Statystyki nowych kont</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            Ilość rozmów kwalifikacyjnych
        </div>
        <div class="panel-body">
            <div class="row">
                <form method="POST" action="{{ URL::to('/pageReportNewAccount') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start" name="date_start" type="text" value="{{$date_start}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres do:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_stop" name="date_stop" type="text" value="{{$date_stop}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel" style="color: #fff;">.</label>
                            <button class="btn btn-info" style="width: 100%">
                                <span class="glyphicon glyphicon-search"></span> Wyszukaj
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                            <table class="table table-striped thead-inverse">
                                <thead>
                                    <tr>
                                        <th>Imie</th>
                                        <th>Nazwisko</th>
                                        <th>Oddział</th>
                                        <th>Ilość dodanych kont</th>
                                        <th>Ilość kont reaktywowanych</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as  $item)
                                        <tr>
                                            <td>{{$item->first_name}}</td>
                                            <td>{{$item->last_name}}</td>
                                            <td>{{$item->counted}}</td>
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



@endsection
@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.select.min.js')}}"></script>
<script>

$('.form_date').datetimepicker({
    language: 'pl',
    autoclose: 1,
    minView: 2,
    pickTime: false,
});


</script>
@endsection