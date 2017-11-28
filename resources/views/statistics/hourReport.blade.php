@extends('layouts.main')
@section('content')
<style>
    button{
        width: 100%;
        height: 33px;
        margin-top: 25px;
        text-align: center;
    }
</style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Raport Godzinny</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Uzupełnij raport
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="well">
                                <div class="panel-body">
                                            <form method="post">
                                                <div class="col-md-3">
                                                    <label for="exampleInputPassword1">Godzina:</label>
                                                    <select name="raportgodzinny_hour" class="form-control" style="font-size:18px;">
                                                        <option>Wybierz</option>
                                                        @for ($i=9; $i < 22; $i++)
                                                            @php($godz = $i.':00')
                                                            @if ($godz == '9:00')
                                                                @php($godz = '09:00')
                                                            @endif
                                                            <option value={{$godz.':00'}}>{{$godz}}</option>
                                                            {{--@if (!isset($_SESSION['raport_godzinny_nowy_show'][$godz1])) --}}
                                                                {{--@if ($_POST['raportgodzinny_hour'] == $godz)--}}
                                                                    {{--<option selected>--}}
                                                                {{--@else--}}
                                                                    {{--<option>--}}
                                                                {{--@endif--}}
                                                                    {{--$godz.'</option>';--}}
                                                           {{--@endif--}}
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="exampleInputPassword1">Średnia:</label>
                                                    <input class="form-control numeric" name="raportgodzinny_average" type="text" value="">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="exampleInputPassword1">Liczba Zaproszeń:</label>
                                                    <input class="form-control" name="raportgodzinny_success" type="number" value="">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="exampleInputPassword1">Liczba Osób:</label>
                                                    <input class="form-control" name="raportgodzinny_users" type="text" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="exampleInputPassword1">% Janków:</label>
                                                    <input class="form-control" name="raportgodzinny_procjan" type="text" value="">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="exampleInputPassword1">% Wykorzystania Bazy</label>
                                                    <input class="form-control" name="raportgodzinny_zb" type="text" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="exampleInputPassword1">Czas Rozmów:</label>
                                                    <input class="form-control" name="raportgodzinny_call_time" type="text" value=""></br>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary" name="raportgodzinny_send" >Wyślij</button>
                                                </div>
                                            </form>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Raport z Dnia
                </div>
                <div class="panel-body">
                    <div class="row">
                        <table class="table table-bordered">
                            <thead>
                            <tr align="center">
                                <th align="center">Godzina</th>
                                <th align="center">Średnia</th>
                                <th align="center">Liczba Zaproszeń</th>
                                <th align="center">Liczba Zalogowanych</th>
                                <th align="center">% Janków</th>
                                <th align="center">% Wykorzystania Bazy</th>
                                <th align="center">Czas Rozmów</th>
                                <th align="center">% Celu</th>
                            </tr>
                            </thead>
                            @for ($i=9; $i < 22; $i++)
                                <tr>
                                @php($godz = $i.':00')
                                @if ($godz == '9:00')
                                    @php($godz = '09:00')
                                @endif
                                <td>{{$godz}}</td>
                                </tr>
                            @endfor
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

<script>


</script>
@endsection
