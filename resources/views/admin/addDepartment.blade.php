@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Dodaj oddział</div>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
   <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{URL::to('/add_department')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="department">Wybierz oddział:</label>
                <select id="department" name="department" class="form-control">
                    <option value="-1">Wybierz</option>
                    @foreach($departments as $dep)
                        <option value="{{$dep->id}}">{{$dep->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label name="city" for="city">Lub dodaj nowy:</label>
                <input type="text" class="form-control" placeholder="Miasto..." name="city" id="city"/>
            </div>


            <div class="form-group">
                <label for="desc">Dodaj opis:</label>
                <input type="text" class="form-control" placeholder="Opis..." name="desc" id="desc" />
            </div>
            <div class="form-group">
                <label for="city">Wybierz typ oddziału:</label>
                <select class="form-control" name="id_dep_type" id="id_dep_type">
                  <option>Wybierz</option>
                  @foreach($department_types as $department_type)
                      <option value="{{$department_type->id}}">{{$department_type->name}}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="city">Wybierz podtyp oddziału:<span style="color:red;">*</span></label>
                <select class="form-control" name="type">
                  <option>Wybierz</option>
                  <option>Badania</option>
                  <option>Wysyłka</option>
                  <option>Badania/Wysyłka</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pbx_id">Id z programu PBX:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" id="pbx_id" name="pbx_id" placeholder="PBX ID">
            </div>
            <div class="form-group">
                <label for="city">System jankowy:<span style="color:red;">*</span></label>
                <select class="form-control" name="janky_system_id">
                  <option>Wybierz</option>
                  <option>Tak</option>
                  <option>Nie</option>
                </select>
            </div>
            <div class="form-group">
                <label for="size">Podaj ilość miejsc dla pracowników:</label>
                <input type="number" class="form-control" placeholder="Ilość miejsc.." name="size" id="size" />
            </div>
            <div class="form-group">
                <label for="commission_avg">Podaj minimalną średnią do premii:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="Średnia.." name="commission_avg" id="commission_avg" />
            </div>
            <div class="form-group">
                <label for="commission_hour">Podaj minimalną godzin do premii:<span style="color:red;">*</span></label>
                <input type="number" class="form-control" placeholder="Godziny.." name="commission_hour" id="commission_hour" />
            </div>
            <div class="form-group">
                <label for="commission_start_money">Podaj premię podstawową:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="10.00" name="commission_start_money" id="commission_start_money" />
            </div>
            <div class="form-group">
                <label for="commission_step">Podaj wartość premii (dodatek co próg punktowy):<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="0.5" name="commission_step" id="commission_step" />
            </div>
            <div class="form-group">
                <label for="commission_janky">% janków dyskwalifikujący z premii:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="5" name="commission_janky" id="commission_janky" />
            </div>
            <div class="form-group">
                <label for="dep_aim">Cel dzienny:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="1200" name="dep_aim" id="dep_aim" />
            </div>
            <div class="form-group">
                <label for="dep_aim_week">Cel weekendowy:<span style="color:red;">*</span></label>
                <input type="text" class="form-control" placeholder="500" name="dep_aim_week" id="dep_aim_week" />
            </div>
            <div class="form-group">
                <label for="work_hour">Podaj liczbę godzin w których działa oddział od pn-pt:</label>
                <input type="number" class="form-control" placeholder="Godziny.." name="work_hour" id="work_hour" />
            </div>
            <div class="form-group">
                <label for="work_hour_weekend">Podaj liczbę godzin w których działa oddział od sb-nd:</label>
                <input type="number" class="form-control" placeholder="Godziny.." name="work_hour_weekend" id="work_hour_weekend" />
            </div>
            <div class="form-group">
                <span style="color:red;">*</span> - Dotyczy oddziałów telemarketingu.
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Dodaj oddział" id="add_department_submit"/>
            </div>
        </form>
    </div>
</div>

@endsection
@section('script')

<script>

$('#add_department_submit').on('click', function() {
    var id_dep_type = $("#id_dep_type").val();
    var department = $('#department').val();
    var city = $('#city').val();

    if (department == '-1' && city == '') {
        swal('Wybierz oddział lub dodaj nowy!')
        return false;
    }

    if (id_dep_type == 'Wybierz') {
        swal('Wybierz typ oddziału!')
        return false;
    }
});

$('#department').on('change', function(){
    var department = $('#department').val();

    if (department != '-1') {
        $('#city').attr('disabled', true);
    } else {
        $('#city').removeAttr('disabled');
    }
});


</script>
@endsection
