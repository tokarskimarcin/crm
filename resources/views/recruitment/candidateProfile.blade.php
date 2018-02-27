@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
    .modal {
        text-align: center;
        padding: 0!important;
      }
      
      .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px; /* Adjusts for spacing */
      }
      
      .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
      }
      option {
        font-size: 20px;
      }
</style>
@php
    $temp1 = $status_to_change->where('id', '=', 2);
    $phone_call_status = $temp1[0]->attemptResult;
    $temp2 = $status_to_change->where('id', '=', 17);
    $interview_status = $temp2[2]->attemptResult;
    $attempt_result_last_active = '';
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Profil kandydata</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Profil kandydata
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-size: 150px; color: #aaa" class="glyphicon glyphicon-user"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <b class="myLabel">
                                        <span id="name_surname">{{$candidate->first_name . ' ' . $candidate->last_name}}</span>
                                    </b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <b class="myLabel">Status rekrutacji:</b>
                                    <p class="myLabel">
                                        <span id="user_status">{{$candidate_status}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="myLabel"></label>
                                    <button class="btn btn-info" style="width: 100%" id="edit_submit">
                                        <span class="glyphicon glyphicon-envelope"></span> Zapisz zmiany
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Imie:</label>
                                        <input type="text" class="form-control" id="candidate_name" placeholder="Imie" value="{{$candidate->first_name}}"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Nazwisko:</label>
                                        <input type="text" class="form-control" id="candidate_surname" placeholder="Nazwisko" value="{{$candidate->last_name}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Numer telefonu:</label>
                                        <input type="text" class="form-control" id="candidate_phone" placeholder="000000000" value="{{$candidate->phone}}"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Oddział:</label>
                                        <select class="form-control" id="candidate_department" disabled>
                                            @foreach($department_info as $item)
                                                <option @if($item->id == $candidate->department_info_id) selected @endif value="{{$item->id}}">{{$item->departments->name . ' ' . $item->department_type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Źródło:</label>
                                        <select class="form-control" id="candidate_source">
                                            @foreach($sources as $item)
                                                <option @if($item->id == $candidate->candidate_source_id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel">Status rekrutacji:</label>
                                        <input type="text" class="form-control status_input" value="{{$candidate_status}}"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel">Doświadczenie:</label>
                                        <select class="form-control" id="candidate_experience">
                                            @if($candidate->experience == 0 )
                                                <option value="0" selected>Brak</option>
                                                <option value="1">Tak</option>
                                            @elseif( $candidate->experience == 1)
                                                <option value="0">Brak</option>
                                                <option value="1" selected>Tak</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Opis:</label>
                                    <textarea rows="5" style="height: 100%" class="form-control" id="candidate_desc" placeholder="Opis pracownika">{{$candidate->comment}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="myLabel">Były pracownik:</label>
                                                <select class="form-control" id="ex_candidate_status">
                                                    @if($candidate->id_user == null )
                                                        <option value="0" selected>Nie</option>
                                                        <option value="1">Tak</option>
                                                    @elseif( $candidate->id_user != null)
                                                        <option value="0">Nie</option>
                                                        <option value="1" selected>Tak</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @php
                                        $name = '';
                                     if($candidate->id_user != null)
                                        $name = $candidate->ex_user->first_name.' '.$candidate->ex_user->last_name;

                                    @endphp
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="myLabel">Dane konta z godzinówki:</label>
                                            <input type="text" class="form-control" id="candidate_ex_date"  value="{{$name}}" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="myLabel" style="color:white">_</label>
                                            <button  class="btn btn-info" id="edit_ex_user" style="width: 100%" @if($candidate->id_user == null) disabled @endif>
                                                <span class="glyphicon glyphicon-edit"></span> Edycja
                                            </button>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="find_user_table" style="display:none" >
                                        <table id="all_user_fired" class="table table-striped thead-inverse">
                                            <thead>
                                            <tr>
                                                <th>Imie i nazwisko</th>
                                                <th class="category_column">Data rozpoczęcia</th>
                                                <th class="category_column">Data zakończenia</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($fired_user as $item)
                                                @if($candidate->id_user == $item->id)
                                                    <tr id={{$item->id}} class="selected">
                                                @else
                                                    <tr id={{$item->id}}>
                                                @endif
                                                    <td>{{$item->first_name.' '.$item->last_name}}</td>
                                                    <td>{{$item->start_work}}</td>
                                                    <td>{{$item->end_work}}</td>
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
                @if($candidate->attempt_status_id == 3)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger" style="color: #616366; font-size: 20px;">
                                    Data rozmowy kwalifikacyjnej: <b>{{$candidate->recruitment_attempt->where('status', '=', 0)->first()->interview_date}}</b>
                            </div>
                        </div>
                    </div>
                @endif

                @if($candidate->attempt_status_id == 5)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3"></div>
                            <div class="col-md-7">
                                <div class="alert alert-danger" style="color: #616366; font-size: 15px;">
                                    Data szkolenia:
                                    <div id="training_date_input" style="display: initial;">
                                        <b id="training_date_edit_old_date">{{$candidate->recruitment_attempt->where('status', '=', 0)->first()->training_date}}</b>
                                        <div id="training_date_edit_new_date" class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width: 100%; display: none;">
                                            <input class="form-control" id="training_date_edit" name="training_date_edit" type="text" value="{{$candidate->recruitment_attempt->where('status', '=', 0)->first()->training_date}}" disabled>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button  class="btn btn-info" id="edit_training_date_button" style="width: 100%; padding: 15px">
                                        <span class="glyphicon glyphicon-edit"></span> Edycja
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@if($candidate->recruitment_attempt->where('status', '=', 0)->count() == 0)
    <div class="form-group">
        <button class="btn btn-success" style="width: 100%" data-toggle="modal" data-target="#newRecruitment">
            <span class="glyphicon glyphicon-ok"></span> Przeprowadź rekrutację
        </button>
    </div>
@endif

@php
    $i = $candidate->recruitment_attempt->count() + 1;
@endphp
@foreach($candidate->recruitment_attempt->sortByDesc('created_at') as $item)
@php
    $i--;
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Rektutacja nr {{$i}} @if($item->status == 1) (Zakończona) @endif
            </div>
            <div class="panel-body">
        
                <ul class="nav nav-tabs" style="margin-bottom: 25px">
                    @php
                        $y = 0;
                        $last_id = $item->recruitment_story->last()->id;
                    @endphp
                    @foreach($item->recruitment_story as $story)
                    @php
                        $y++;
                    @endphp
                        <li @if($story->id == $last_id) class="active" @endif><a data-toggle="tab" href="#story{{$story->id}}">Etap {{$y}}</a></li>
                    @endforeach
                </ul>
                
                <div class="tab-content">
                    @php
                        $y = 0;
                    @endphp
                    @foreach($item->recruitment_story as $story)
                    @php
                        $y++;
                    @endphp
                        <div id="story{{$story->id}}" class="tab-pane fade in @if($story->id == $last_id) active @endif">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{$story->attemptLevel->name}}
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="myLabel">Rekruter:</label>
                                                <input type="text" class="form-control" readonly value="{{$story->cadre->first_name . ' ' . $story->cadre->last_name}}"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="myLabel">Data:</label>
                                                <input type="text" class="form-control" value="{{$story->updated_at}}" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="myLabel">Etap rekrutacji:</label>
                                                <input type="text" class="form-control" readonly value="{{$story->attemptLevel->name}}"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="myLabel">Wynik Etapu</label>
                                            @php
                                                $attempt_result_last = 'Brak';
                                                if(isset($story->attemptResult->name)){
                                                        if($item->status == 0){
                                                            $attempt_result_last_active = $story->attemptResult->name;
                                                        }
                                                    $attempt_result_last = $story->attemptResult->name;
                                            }
                                            @endphp

                                            <input type="text" class="form-control" readonly value="{{$attempt_result_last}}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button data-toggle="modal" data-target="#nextLevel" class="btn btn-success" style="width: 100%" @if(($item->status == 1) || ($candidate->attempt_status_id >= 5 && $candidate->attempt_status_id <= 16)) disabled @endif>  
                                                        <span class="glyphicon glyphicon-ok"></span> Następny etap
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button data-toggle="modal" data-target="#add_training" class="btn btn-warning" style="width: 100%" @if(($item->status == 1) || (in_array($candidate->attempt_status_id, [5,6,7,9,12,13,14,15,16]))) disabled @endif>  
                                                        <span class="glyphicon glyphicon-envelope"></span> Zapisz na szkolenie
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button data-toggle="modal" data-target="#stopRecruitment" class="btn btn-danger" style="width: 100%" @if($item->status == 1) disabled title="Rekrutacja zakończona!" @endif>  
                                                        <span class="glyphicon glyphicon-remove"></span> Zakończ rekrutację
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="myLabel">Komentarz:</label>
                                                <textarea rows="4" readonly class="form-control" placeholder="Komentarz">{{$story->comment}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<div id="newRecruitment" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dodaj etap rekrutacji</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="myLabel">Etap rekrutacji:</label>
                            <input type="text" readonly class="form-control" value="Rozpoczęcie rekrutacji">
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Komentarz:</label>
                            <textarea class="form-control" rows="5" placeholder="Dodaj komentarz..." id="new_recruitment_comment"></textarea>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-success" id="new_recruitment_submit">
                                <span class="glyphicon glyphicon-ok"></span> Rozpocznij rekrutację
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknj</button>
            </div>
        </div>
    </div>
</div>

<div id="stopRecruitment" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Zakończ rekrutację</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="myLabel">Etap rekrutacji:</label>
                            <input class="form-control" id="stop_recruitment_status" value="Zakończenie rekrutacji ({{$candidate->attempt_level_data->name}})" readonly>
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Wynik Etapu:</label>
                            <select class="form-control" id="select_last_attempt_result">
                                       @foreach($candidate->attempt_level_data->attemptResult as $item)
                                            @if($attempt_result_last_active == $item->name)
                                                <option selected value={{$item->id}}>{{$item->name.' '}}</option>
                                            @else
                                                <option value={{$item->id}}>{{$item->name.' '}}</option>
                                            @endif
                                       @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Powód:</label>
                            <textarea class="form-control" rows="5" placeholder="Dodaj komentarz..." id="stop_recruitment_comment"></textarea>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-danger" id="stop_recruitment_submit">
                                <span class="glyphicon glyphicon-ok"></span> Zakończ rekrutację
                            </button>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-success" id="stop_recruitment_add">
                                <span class="glyphicon glyphicon-ok"></span> Zakończ i dodaj jako konsultanta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknj</button>
            </div>
        </div>
    </div>
</div>

<div id="nextLevel" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dodaj etap rekrutacji</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="myLabel">Wybierz etap rekrutacji:</label>
                            <select class="form-control" id="add_level_status">
                                @foreach($status_to_change as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row" id="phone_call_status_div">
                            <div class="col-md-12">
                                <div class=form-group"">
                                    <label class="myLabel">Wybierz status etapu:</label>
                                    <select class="form-control" id="after_call">
                                        <option>Wybierz</option> 
                                        @foreach($phone_call_status as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="after_interview_div" style="display: none">
                            <div class="col-md-12">
                                <div class=form-group"">
                                    <label class="myLabel">Wybierz status etapu:</label>
                                    <select class="form-control" id="after_interview">
                                        <option>Wybierz</option>
                                        @foreach($interview_status as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="inverview_date_div" style="display:none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Dodaj datę rozmowy kwalifikacyjnej</label>
                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control" id="interview_date" name="interview_date" type="text" value="{{date("Y-m-d")}}" >
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="myLabel">Dodaj godzinę rozmowy kwalifikacyjnej</label>
                                <input type="time" class="form-control" id="interview_time" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Komentarz:</label>
                            <textarea class="form-control" rows="5" placeholder="Dodaj komentarz..." id="add_level_comment"></textarea>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-success" id="add_level_submit">
                                <span class="glyphicon glyphicon-ok"></span> Dodaj etap
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknj</button>
            </div>
        </div>
    </div>
</div>

<div id="add_training" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dodaj etap rekrutacji</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="myLabel">Etap rekrutacji:</label>
                            <input class="form-control" value="Zapis na szkolenie" readonly>
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Komentarz:</label>
                            <textarea class="form-control" rows="5" placeholder="Dodaj komentarz..." id="add_training_comment"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="myLabel">Data szkolenia</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="training_date" name="training_date" type="text" value="{{date("Y-m-d")}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-warning" id="add_training_submit">
                                <span class="glyphicon glyphicon-ok"></span> Zapisz na szkolenie
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknj</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="candidate_id" value="{{$candidate->id}}" />
<input type="hidden" id="candidate_id" value="{{$candidate->id}}" />
@endsection
@section('script')
<script>

    var ex_id_user = '{{$candidate->id_user}}';
    //ilość kiliknięć edycji szkolenia
    var click = 0;
    //zapisanie daty szkolenia
    var input_div_text = '';
$('.form_date').datetimepicker({
    language: 'pl',
    autoclose: 1,
    minView: 2,
    pickTime: false,
});


$(document).ready(() => {

    var ex_candidate_date = $('#candidate_ex_date').val();

    $('#ex_candidate_status').on('change',function (e) {
        if($(this).val() == 0)
        {
            $('#candidate_ex_date').val('');
            $('#edit_ex_user').attr("disabled",true);
            ex_id_user = null;
            $('.find_user_table').css("display","none");
            ex_candidate_date = '';

            if($('#edit_ex_user').hasClass('is_open'))
            {
                $('#edit_ex_user').removeClass('is_open');
                $('.find_user_table').css("display","none");
            }
        }else if($(this).val() == 1)
        {
            $('#candidate_ex_date').val(ex_candidate_date);
            $('#edit_ex_user').attr("disabled",false);
        }
    });

        //tablela z byłymi pracownikami
        var fired_user_table = $('#all_user_fired').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            }
        });

        // event, po kliknięciu wiersza w tabeli z byłymi pracownikami
        $('#all_user_fired').on('click','tr',function (e) {
            if($(this).hasClass('selected')){
                $(this).removeClass('selected');
                ex_id_user = null;
                $('#candidate_ex_date').val('');
                ex_candidate_date = '';
            }else{
                fired_user_table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                ex_id_user = fired_user_table.row('.selected').data()['DT_RowId'];
                ex_candidate_date = fired_user_table.row('.selected').data()[0];
                $('#candidate_ex_date').val(ex_candidate_date);
            }
        });
        $('#edit_ex_user').on('click',function (e) {
            if($(this).hasClass('is_open'))
            {
                $(this).removeClass('is_open');
                $('.find_user_table').css("display","none");
            }else{
                $(this).addClass('is_open');
                $('.find_user_table').css("display","block");
            }
        })

    $('#add_level_status').change(() => {
        var add_level_status = $('#add_level_status').val();

        if (add_level_status == 3) {
            $('#inverview_date_div').fadeIn(500);
            $('#phone_call_status_div').fadeOut(500);      
            $('#after_interview_div').fadeOut(500);      
        } else if(add_level_status == 2) {
            $('#inverview_date_div').fadeOut(500);
            $('#after_interview_div').fadeOut(500);
            $('#phone_call_status_div').fadeIn(500);
        } else if (add_level_status == 17) {
            $('#phone_call_status_div').fadeOut(500);
            $('#inverview_date_div').fadeOut(500);
            $('#after_interview_div').fadeIn(500);
        }
    });

    // Edycja daty szkolenia
    $('#edit_training_date_button').click(() => {
        // gdy została kliknięta edycja przycisku

        if(click == 0){
            $('#training_date_edit_old_date').hide();
            $('#training_date_edit_new_date').show();
            $('#edit_training_date_button').html('<span class="glyphicon glyphicon-edit"></span> Zapisz');
            click++;
        }else{
            //Edycja daty szkolenia
            var training_date = $('#training_date_edit').val();
            $.ajax({
                type: "POST",
                url: '{{ route('api.editTrainingDate') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "training_date": training_date,
                    "candidate_id": $('#candidate_id').val()
                },
                success: function (response) {
                    console.log(response);
                    $('#training_date_edit_old_date').text(training_date);
                    $('#training_date_edit_old_date').show();
                    $('#training_date_edit_new_date').hide();
                    $('#edit_training_date_button').html('<span class="glyphicon glyphicon-edit"></span> Edycja');
                    click--;
                }
            });
        }
    });
    $('#edit_submit').click(() => {
        var candidate_id = $('#candidate_id').val();
        var candidate_name = $('#candidate_name').val();
        var candidate_surname = $('#candidate_surname').val();
        var candidate_phone = $('#candidate_phone').val();
        var candidate_department = $('#candidate_department').val();
        var candidate_source = $('#candidate_source').val();
        var candidate_desc = $('#candidate_desc').val();
        var candidate_experience = $('#candidate_experience').val();
        var ex_candidate_status = $('#ex_candidate_status').val();
        if(ex_id_user == '')
            ex_id_user = null;
        if(ex_candidate_status == 1 && ex_id_user == null ){
            swal('Wybierz byłego pracownika z listy')
            return false;
        }

        if (candidate_name == '' || (candidate_name.trim().length == 0)) {
            swal('Podaj imie kandydata!')
            return false;
        }

        if (candidate_surname == '') {
            swal('Podaj nazwisko kandydata!')
            return false;
        }

        if (candidate_phone == '' || (candidate_phone.trim().length == 0)) {
            swal('Podaj telefon kandydata!')
            return false;
        } else if (isNaN(candidate_phone) || (candidate_phone.length < 8)) {
            swal('Podaj prawidłowy numer telefonu!')
            return false;
        }

        if (candidate_department == 'Wybierz') {
            swal('Wybierz oddział!')
            return false;
        }

        if (candidate_source == 'Wybierz') {
            swal('Wybierz żródło!')
            return false;
        }

        if (candidate_desc == '' || (candidate_desc.trim().length == 0)) {
            swal('Dodaj opis kandydata!')
            return false;
        }

        //Czasowe zablokowanie przycisku
        $('#edit_submit').attr('disabled', true);

        //Edycja danych kandydata
        $.ajax({
            type: "POST",
            url: '{{ route('api.editCandidate') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "candidate_name": candidate_name,
                "candidate_surname": candidate_surname,
                "candidate_phone": candidate_phone,
                "candidate_department": candidate_department,
                "candidate_source": candidate_source,
                "candidate_desc": candidate_desc,
                "candidate_experience":candidate_experience,
                "ex_id_user":ex_id_user
            },
            success: function (response) {
                if (response == 1) {
                    swal('Zmiany zapisano!')
                    var newName = candidate_name + " " + candidate_surname;
                    $('#name_surname').html(newName);
                    $('.find_user_table').css("display","none");
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
                //Odblokowanie przycisku
                $('#edit_submit').attr('disabled', false);
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                //Odblokowanie przycisku
                $('#edit_submit').attr('disabled', false);
            }
        });
    });

    $('#new_recruitment_submit').click(() => {
        var candidate_id = $('#candidate_id').val();
        var new_recruitment_comment = $('#new_recruitment_comment').val();

        if (new_recruitment_comment == '' || (new_recruitment_comment.trim().length == 0)) {
            swal('Dodaj komentarz!')
            return false;
        }

        $('#new_recruitment_submit').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: '{{ route('api.startNewRecruitment') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "new_recruitment_status": 1,
                "new_recruitment_comment": new_recruitment_comment
            },
            success: function (response) {
                if (response == 1) {
                    swal('Rekrutacja została rozpoczęta!')
                    location.reload();
                } else if (response == 2) {
                    swal('Pracownik ma już rozpoczętą rekrutację!')
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
        $('#new_recruitment_submit').prop('disabled', false);
    });

    function stopRecruitment(stopType) {
        var candidate_id = $('#candidate_id').val();
        //var stop_recruitment_status = $('#stop_recruitment_status').val();
        var stop_recruitment_comment = $('#stop_recruitment_comment').val();

        var stop_recruitment_status = (stopType == 0) ? 11 : 10 ;

        if (stop_recruitment_comment == '' || (stop_recruitment_comment.trim().length == 0)) {
            swal('Dodaj komentarz!')
            return false;
        }

        $('#stop_recruitment_submit').prop('disabled', true);
        $('#stop_recruitment_add').prop('disabled', true);
        // result attempt id
        var last_attempt_result = $('#select_last_attempt_result').val();
        $.ajax({
            type: "POST",
            url: '{{ route('api.stopRecruitment') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "stop_recruitment_status": stop_recruitment_status,
                "stop_recruitment_comment": stop_recruitment_comment,
                "stopType": stopType,
                "last_attempt_result" : last_attempt_result
            },
            success: function (response) {
                if (response == 1) {
                    swal('Rekrutacja została zakończona!')
                    location.reload();
                } else if (response == 2) {
                    window.location.href = "{{ URL::to('/add_consultant') }}";
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    }

    $('#stop_recruitment_submit').click(() => {
        var stopType = 0; // tutaj jezeli nie dodajemy jako konsultanta
        stopRecruitment(stopType);
    });

    $('#stop_recruitment_add').click(() => {
        var stopType = 1; // tutaj jezeli dodajemy jako konsultanta
        stopRecruitment(stopType);
    });

    $('#add_level_submit').click(() => {
        var candidate_id = $('#candidate_id').val();
        var add_level_status = $('#add_level_status').val();
        var add_level_comment = $('#add_level_comment').val();
        var after_interview = $('#after_interview').val();
        var after_call = $('#after_call').val();

        if (add_level_comment == '' || (add_level_comment.trim().length == 0)) {
            swal('Dodaj komentarz!')
            return false;
        }

        //Jezeli umowiony na rozmowe kwalifikacyjną
        if (add_level_status == 3) {
            var interview_date = $('#interview_date').val();
            var interview_time = $('#interview_time').val();

            if (interview_time == '') {
                swal('Podaj godzinę rozmowy kwalifikacyjnej!')
                return false;
            }

            var interview = interview_date + " " + interview_time +":00";
        } else {
            var interview = null;
        }

        //Jezeli jest to rozmowa telefoniczna
        if (add_level_status == 2 && after_call == 'Wybierz') {
            swal('Wybierz status etapu!');
            return false;
        }

        //Jezeli jest to wynik romzowy kwalifikacyjnej
        if (add_level_status == 17 && after_interview == 'Wybierz') {
            swal('Wybierz status etapu!');
            return false;
        }

        $('#add_level_submit').prop('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: '{{ route('api.addRecruitmentLevel') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "add_level_status": add_level_status,
                "add_level_comment": add_level_comment,
                "interview": interview,
                "after_call": after_call,
                "after_interview": after_interview
            },
            success: function (response) {
                if (response == 1) {
                    swal('Etap został dodany!')
                    location.reload();
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    });

    $('#add_training_submit').click(() => {
        var candidate_id = $('#candidate_id').val();
        var date_training = $('#training_date').val();
        var add_training_comment = $('#add_training_comment').val();

        if (add_training_comment == '' || (add_training_comment.trim().length == 0)) {
            swal('Dodaj komentarz!')
            return false;
        }

        $('#add_training_submit').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: '{{ route('api.addToTraining') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "add_training_comment": add_training_comment,
                "add_level_status": 5,
                "date_training":date_training
            },
            success: function (response) {
                if (response == 1) {
                    swal({
                        title: 'Etap został dodany!',
                        type: 'success',
                        timer: 3000
                    }).then((result) => {
                        window.location.href = "{{ URL::to('/add_group_training') }}";
                    })
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    });
});
</script>
@endsection
