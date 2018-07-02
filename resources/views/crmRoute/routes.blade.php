<style>
    .routes-wrapper {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .routes-container, .new-route-container, .delete-container {
        background-color: white;
        padding: 2em;
        box-shadow: 0 1px 15px 1px rgba(39, 39, 39, .1);
        border: 0;
        border-radius: .1875rem;
        margin: 1em;

        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 90%;
        max-width: 90%;

    }

    header {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
        padding-bottom: .5em;
    }

    .glyphicon-remove {
        font-size: 2em;
        transition: all 0.8s ease-in-out;
        float: right;
        color: red;
    }

    .glyphicon-remove:hover {
        transform: scale(1.2) rotate(180deg);
        cursor: pointer;
    }

    .glyphicon-refresh {
        font-size: 2em;
        transition: all 1.8s ease-in-out;
        color: #0f10ff;
    }

    .glyphicon-refresh:hover {
        transform: scale(1.2) rotate(360deg);
        cursor: pointer;
    }

    .header {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
        box-shadow: 0 1px 15px 1px rgba(39, 39, 39, .1);
        width: 100%;
        padding-top: 1em;
        padding-bottom: 1em;
    }

    .second_button_section {
        display: flex;
        flex-direction: column;
    }

    .infobuttons {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .importantButtons {
        display: flex;
        flex-direction: column;
    }


</style>
<div class="routes-wrapper">
    @if(Session::has('adnotation'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">{{Session::get('adnotation') }}</div>
            </div>
        </div>
        @php
            Session::forget('adnotation');
        @endphp
    @endif
    {{--<div class="header">
@if(isset($editFlag))
            <span>Edytuj trasę</span>
    @else
            <span>Nowa trasa</span>
    @endif
        </div>--}}

    @php
        $lp = 1;
    @endphp

    @if(isset($routeInfo))
        @foreach($routeInfo as $routeInf)
            <div class="routes-container">
                <div class="row">

                    @if($lp != 1)
                        <div class="button_section button_section_gl_nr">
                            <span class="glyphicon glyphicon-remove" data-remove="show"></span>
                        </div>
                    @endif
                    <header>Pokaz</header>
                    @if($lp != 1)
                        <div class=colmd-12 style="text-align: center">
                            <span class="glyphicon glyphicon-refresh" data-refresh="refresh"
                                  style="font-size: 30px"></span>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Województwo</label>
                            <select class="form-control voivodeship" data-type="voivode" data-element="voivode">
                                <option value="0">Wybierz</option>
                                @foreach($voivodes as $voivode)
                                    <option value="{{$voivode->id}}"
                                            @if($voivode->id == $routeInf->voivodeship_id) selected @endif>{{$voivode->name}}</option>
                                    '
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city">Miasto</label>
                            <select class="form-control city">
                                <option value="0">Wybierz</option>
                                @foreach($routeInf->cities as $city)
                                    @if($city->city_id == $routeInf->city_id)
                                        <option value="{{$routeInf->city_id}}"
                                                selected>{{$routeInf->city->name}}</option>
                                    @elseif ($routeInf->voivodeship_id == $city->id)
                                        <option value="{{$city->city_id}}">{{$city->city_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group hour_div">
                    </div>
                </div>
            </div>


            @php
                $lp++;
            @endphp
        @endforeach
        <div class="new-route-container">
            <div class="row">
                <div class="col-lg-12 button_section second_button_section">

                    <div class="infobuttons">
                        <button class="btn btn-primary" id="return"
                                style="margin-bottom:1em;width:47%;font-size:1.1em;font-weight:bold;"><span class='glyphicon glyphicon-repeat'></span> Powrót</button>
                        <button class="btn btn-default btn_add_new_route" id="add_new_show"
                                style="width:47%;margin-bottom:1em;font-size:1.1em;font-weight:bold;"><span class="glyphicon glyphicon-plus"></span> Dodaj nowy Pokaz</button>
                    </div>
                    <div class="importantButtons">
                        {{--<input type="button" class="btn btn-danger" value="Usuń trasę" data-element="usun" style="margin-bottom:1em;width:100%;font-size:1.1em;font-weight:bold;">--}}
                        <button class="btn btn-success" id="save_route"
                                style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;"><span class='glyphicon glyphicon-save'></span> Zapisz</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

