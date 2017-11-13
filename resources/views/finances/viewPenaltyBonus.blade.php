@extends('layouts.main')
@section('style')

@endsection

@section('content')

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Kary i Premie</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Podgląd Wypłat
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">


                                <div class="col-md-12">
                                    <h1 style ="font-family: 'bebas_neueregular'; text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.8); font-size:25px; margin-top: 0;">Przydziel Karę/Premię</h1>

                                    <div class="well">
                                        <div class="form-group">
                                            <form action="view_penalty_bonus" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-6">
                                                    <label for="exampleInputPassword1">Pracownik:</label>
                                                    <select name="user_id" class="form-control" style="font-size:18px;">
                                                        <option>Wybierz</option>
                                                        @foreach($users as $user)
                                                            <option value={{$user->id}}>{{$user->last_name.' '.$user->first_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Data:</label>
                                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">

                                                        <input class="form-control" name="date_penalty" type="text" value="{{date("Y-m-d")}}" readonly >
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                    </div></br>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Kara/Premia:</label>
                                                    <select name="type_penalty" class="form-control" style="font-size:18px;">
                                                        <option>Wybierz</option>
                                                        <option value="1">Kara</option>
                                                        <option value="2">Premia</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Kwota:</label>
                                                    <input class="form-control" name="cost" type="number" min="0" step="1" value=""></br>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="exampleInputPassword1">Powód:</label>
                                                    <input class="form-control" name="reason" type="text" value="">

                                                    </br>
                                                </div></br></br></br></br></br></br></br></br></br></br></br></br>
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary" name="addpbsubmit" style="font-size:18px; width:100%;">Zatwierdź</button>
                                                </div>
                                            </form>
                                        </div></br>
                                    </div>
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
        language: 'pl',
        autoclose: 1,
        minView: 2,
        pickTime: false,
    });

</script>
@endsection
