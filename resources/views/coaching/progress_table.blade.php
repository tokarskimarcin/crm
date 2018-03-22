@extends('layouts.main')
@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="well gray-nav">Tabela postępów</div>
            </div>
        </div>
    </div>

    <button data-toggle="modal" class="btn btn-default training_to_modal" id="new_coaching_modal" data-target="#Modal_Coaching" data-id="1" title="Nowy Coaching" style="margin-bottom: 14px">
        <span class="glyphicon glyphicon-plus"></span> <span>Nowy Coaching</span>
    </button>

    {{--Tabela z coaching w toku--}}
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                W toku
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start" name="date_start_in_progress" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres do:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_stop" name="date_stop_in_progress" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                                <table id="table_in_progress" class="table table-striped thead-inverse">
                                    <thead>
                                    <tr>
                                        <th>Trener</th>
                                        <th>Konsultant</th>
                                        <th>Data</th>
                                        <th>Temat</th>
                                        <th>Wynik</th>
                                        <th>Cel</th>
                                        <th>Akcja</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{--Tabela z coaching w Nierozliczone--}}
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                Nierozliczone
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start" name="date_start_unsettled" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres do:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_stop" name="date_stop_unsettled" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table_unsettled" class="table table-striped thead-inverse">
                                <thead>
                                <tr>
                                    <th>Trener</th>
                                    <th>Konsultant</th>
                                    <th>Data</th>
                                    <th>Temat</th>
                                    <th>Wynik</th>
                                    <th>Cel</th>
                                    <th>Komentarz</th>
                                    <th>Akcja</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--Tabela z coaching w Rozliczone--}}
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                Rozliczone
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start" name="date_start_settled" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres do:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_stop" name="date_stop_settled" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table_settled" class="table table-striped thead-inverse">
                                <thead>
                                <tr>
                                    <th>Trener</th>
                                    <th>Konsultant</th>
                                    <th>Data</th>
                                    <th>Temat</th>
                                    <th>Wynik</th>
                                    <th>Cel</th>
                                    <th>Komentarz</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="Modal_Coaching" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title">Ustal Coaching<span id="modal_category"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="header_modal">

                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel">Konsultant</label>
                                        <select class="form-control" id="couaching_user_id">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Temat</label>
                                        <input type="text" class="form-control" id="coaching_subject"/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Data Coaching'u:</label>
                                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                            <input class="form-control" id="date_start" name="date_start_new_coaching" type="text" value="{{date('Y-m-d')}}" >
                                            <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Cel od</label>
                                        <input type="number" class="form-control" id="coaching_goal_min"/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Cel do</label>
                                        <input type="number" class="form-control" id="coaching_goal_max"/>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function(){
            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });
        })
    </script>
@endsection
