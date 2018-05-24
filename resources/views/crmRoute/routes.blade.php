<style>
    .routes-wrapper {
        display:flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .routes-container {
        background-color: white;
        padding: 2em;
        box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
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
        color:red;
    }
    .glyphicon-remove:hover {
        transform: scale(1.2) rotate(180deg);
        cursor: pointer;
    }

    .header {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
        box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
        width: 100%;
        padding-top: 1em;
        padding-bottom: 1em;
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
        <div class="header">
    @if(isset($editFlag))
                <span>Edytuj trasę</span>
        @else
                <span>Nowa trasa</span>
        @endif
            </div>



    @if(isset($routeInfo))
        @foreach($routeInfo as $routeInf)
            <div class="routes-container">
                <div class="row">
                <div class="button_section button_section_gl_nr">
                    <span class="glyphicon glyphicon-remove" data-remove="show"></span>
                </div>
                <header>Pokaz </header>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Województwo</label>
                        <select class="form-control voivodeship" data-type="voivode" data-element="voivode">
                            <option value="0">Wybierz</option>
                            @foreach($voivodes as $voivode)
                                <option value ="{{$voivode->id}}" @if($voivode->id == $routeInf->voivodeship_id) selected @endif>{{$voivode->name}}</option>'
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">Miasto</label>
                        <select class="form-control city">
                            <option value="0">Wybierz</option>
                            <option value="{{$routeInf->city_id}}" selected>{{$routeInf->city->name}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group hour_div">
                </div>
                <div class="col-lg-12 button_section second_button_section">
                    <input type="button" class="btn btn-danger" value="Usuń trasę" data-element="usun" style="width:100%;font-size:1.1em;font-weight:bold;margin-bottom:1em;margin-top:1em;">
                    <input type="button" class="btn btn-success" id="save_route" value="Zapisz!" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">
                    <input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nowy pokaz" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">
                </div>
                </div>
            </div>
            @endforeach
        @endif
</div>

