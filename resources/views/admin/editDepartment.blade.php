@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Edytuj oddział</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{URL::to('/edit_department')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="post_type" value="1" />
            <div class="form-group">
                <select class="form-control" name="selected_department_info_id">
                    @foreach($department_info as $department)
                        <option @if(isset($selected_department) && $selected_department->id == $department->id) selected @endif value="{{$department->id}}">{{$department->departments->name . ' ' . $department->department_type->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Wybierz oddział" class="btn btn-info" >
            </div>
        </form>
    </div>
</div>
@if (Session::has('message_ok'))
   <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif
<hr><br />
@if(isset($selected_department))
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{URL::to('/edit_department')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="post_type" value="2" />
            <input type="hidden" name="selected_department_info_id" value="{{$selected_department->id}}" />
            <div class="form-group">
                <label for="city">Podaj miasto:</label>
                <input type="text" class="form-control" placeholder="Miasto..." name="city" id="city" value="{{$selected_department->departments->name}}" disabled/>
            </div>
            <div class="form-group">
                <label for="desc">Dodaj opis:</label>
                <input type="text" class="form-control" placeholder="Opis..." name="desc" id="desc" value="{{$selected_department->departments->desc}}" />
            </div>
            <div class="form-group">
                <label for="city">Wybierz typ oddziału:</label>
                <select class="form-control" name="id_dep_type" id="id_dep_type">
                  @foreach($department_types as $department_type)
                      <option @if($selected_department->department_type->id == $department_type->id) selected @endif value="{{$department_type->id}}">{{$department_type->name}}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="city">Wybierz podtyp oddziału:<span style="color:red;">*</span></label>
                <select class="form-control" name="type">
                  <option>Wybierz</option>
                  <option @if($selected_department->type == 'Badania') selected @endif>Badania</option>
                  <option @if($selected_department->type == 'Wysyłka') selected @endif>Wysyłka</option>
                  <option @if($selected_department->type == 'Badania/Wysyłka') selected @endif>Badania/Wysyłka</option>
                </select>
            </div>

            <div class="form-group">
                <label for="menager">Kierownik oddziału</label>
                <select class="form-control" name="menager" id="menager">
                    <option value=0>Wybierz</option>
                    @foreach($menagers as $m)
                        <option value="{{$m->id}}" @if(isset($selected_department->menager_id) && $selected_department->menager_id == $m->id) selected @endif>{{$m->first_name . ' ' . $m->last_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="director">Dyrektor oddziału</label>
                <select class="form-control" name="director" id="director">
                    <option value=0>Wybierz</option>
                    @foreach($menagers as $d)
                        <option value="{{$d->id}}" @if(isset($selected_department->director_id) && $selected_department->director_id == $d->id) selected @endif>{{$d->first_name . ' ' . $d->last_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="director_hr">Dyrektor HR</label>
                <select class="form-control" name="director_hr" id="director_hr">
                    <option value=0>Wybierz</option>
                    @foreach($hrDirectors as $hrDirector)
                        <option value="{{$hrDirector->id}}" @if(isset($selected_department->director_hr_id) && $selected_department->director_hr_id == $hrDirector->id) selected @endif>{{$hrDirector->first_name . ' ' . $hrDirector->last_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="hrEmployee">Pracownik HR</label>
                <select class="form-control" name="hrEmployee" id="hrEmployee">
                    <option value=0>Wybierz</option>
                    @foreach($hrEmployee as $h)
                        <option value="{{$h->id}}" @if(isset($selected_department->hr_id) && $selected_department->hr_id == $h->id) selected @endif>{{$h->first_name . ' ' . $h->last_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="pbx_id">Id z programu PBX:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" id="pbx_id" name="pbx_id" value="{{$selected_department->pbx_id}}" placeholder="PBX ID">
            </div>
            <div class="form-group">
                <label for="city">System jankowy:<span style="color:red;">*</span></label>
                <select class="form-control" name="janky_system_id">
                  <option value="1" @if($selected_department->janky_system_id == 1) selected @endif>Tak</option>
                  <option value="0" @if($selected_department->janky_system_id == 0) selected @endif>Nie</option>
                </select>
            </div>
            <div class="form-group">
                <label for="size">Podaj ilość miejsc dla pracowników:</label>
                <input type="number" class="form-control" placeholder="Ilość miejsc.." name="size" id="size" value="{{$selected_department->size}}" />
            </div>
            <div class="form-group">
                <label for="commission_avg">Podaj minimalną średnią do premii:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="Średnia.." name="commission_avg" id="commission_avg"  value="{{$selected_department->commission_avg}}"/>
            </div>
            <div class="form-group">
                <label for="commission_hour">Podaj minimalną godzin do premii:<span style="color:red;">*</span></label>
                <input type="number" class="form-control" placeholder="Godziny.." name="commission_hour" id="commission_hour" value="{{$selected_department->commission_hour}}" />
            </div>
            <div class="form-group">
                <label for="commission_start_money">Podaj premię podstawową:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="10.00" name="commission_start_money" id="commission_start_money" value="{{$selected_department->commission_start_money}}" />
            </div>
            <div class="form-group">
                <label for="commission_step">Podaj wartość premii (dodatek co próg punktowy):<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="0.5" name="commission_step" id="commission_step" value="{{$selected_department->commission_step}}" />
            </div>
            <div class="form-group">
                <label for="commission_janky">% janków dyskwalifikujący z premii:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="5" name="commission_janky" id="commission_janky" value="{{$selected_department->commission_janky}}" />
            </div>
            <div class="form-group">
                <label for="dep_aim">Cel dzienny:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="1200" name="dep_aim" id="dep_aim" value="{{$selected_department->dep_aim}}"/>
            </div>
            <div class="form-group">
                <label for="dep_aim_week">Cel weekendowy:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="500" name="dep_aim_week" id="dep_aim_week" value="{{$selected_department->dep_aim_week}}"/>
            </div>
            <div class="form-group">
                <label for="work_hour">Podaj liczbę godzin w których działa oddział od pn-pt:</label>
                <input type="number" class="form-control" placeholder="Godziny.." name="work_hour" id="work_hour" value="{{$selected_department->working_hours_normal}}" />
            </div>
            <div class="form-group">
                <label for="work_hour_weekend">Podaj liczbę godzin w których działa oddział od sb-nd:</label>
                <input type="number" class="form-control" placeholder="Godziny.." name="work_hour_weekend" id="work_hour_weekend" value="{{$selected_department->working_hours_week}}" />
            </div>
            <div class="form-group">
                <span style="color:red;">*</span> - Dotyczy oddziałów telemarketingu.
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Zapisz zmiany" id="add_department_submit"/>
            </div>
        </form>
    </div>
</div>

@endif


@endsection
@section('script')

<script>



</script>
@endsection
