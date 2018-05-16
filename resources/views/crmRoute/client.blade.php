<style>
    .client-wrapper {
        display:flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .client-container {
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
    }




</style>


<div class="row">

</div>
<div class="client-wrapper">
    <div class="client-container">
        <header>Klient</header>
        <div class="col-md-12">
            <button data-toggle="modal" class="btn btn-default new_client_to_modal" id="new_client_modal" data-target="#Modal_Client" data-id="1" title="Nowy Klient" style="margin-bottom: 14px">
                <span class="glyphicon glyphicon-plus"></span> <span>Dodaj Klienta</span>
            </button>
            <div class="table-responsive">
                <table id="table_client" class="table table-striped thead-inverse">
                    <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Telefon</th>
                        <th>Typ</th>
                        <th style="text-align: center">Akcja</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr id="clientId_1">
                            <td class="client_name">Exito - Vigor Life</td>
                            <td class="client_phone">798987985</td>
                            <td class="client_type">Kamery</td>
                            <td>
                                <button class="btn btn-info"  data-id=1 onclick = "edit_client(this)" >Edycja</button>
                                <button class="btn btn-danger" data-id=1 onclick = "edit_client(this)" >Wyłącz</button>
                            </td>
                            <td>
                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                            </td>
                        </tr>
                        <tr id="clientId_2">
                            <td class="client_name">Pollana Med - Pro Active	</td>
                            <td class="client_phone">798987985</td>
                            <td class="client_type">Kamery</td>
                            <td>
                                <button class="btn btn-info" data-id=2 onclick = "edit_client(this)" >Edycja</button>
                                <button class="btn btn-danger" data-id=2 onclick = "edit_client(this)" >Wyłącz</button>
                            </td>
                            <td>
                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                            </td>
                        </tr>
                        <tr id="clientId_3">
                            <td class="client_name">Damages</td>
                            <td class="client_phone">798987985</td>
                            <td class="client_type">Badania</td>
                            <td>
                                <button class="btn btn-info" data-id=3 onclick = "edit_client(this)" >Edycja</button>
                                <button class="btn btn-danger" data-id=3 onclick = "edit_client(this)" >Wyłącz</button>
                            </td>
                            <td>
                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                            </td>
                        </tr>
                        <tr id="clientId_4">
                            <td class="client_name">PromoMedi</td>
                            <td class="client_phone">798987985</td>
                            <td class="client_type">Kamery</td>
                            <td>
                                <button class="btn btn-info" data-id=4 onclick = "edit_client(this)" >Edycja</button>
                                <button class="btn btn-danger" data-id=4 onclick = "edit_client(this)" >Wyłącz</button>
                            </td>
                            <td>
                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                            </td>
                        </tr>
                        <tr id="clientId_5">
                            <td class="client_name">MarMed</td>
                            <td class="client_phone">798987985</td>
                            <td class="client_type">Badania</td>
                            <td>
                                <button class="btn btn-info" data-id=5 onclick = "edit_client(this)" >Edycja</button>
                                <button class="btn btn-danger" data-id=5 onclick = "edit_client(this)" >Wyłącz</button>
                            </td>
                            <td>
                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                            </td>
                        </tr>
                        <tr id="clientId_6">
                            <td class="client_name">Exito - Vigor Life</td>
                            <td class="client_phone">798987985</td>
                            <td class="client_type">Badania</td>
                            <td>
                                <button class="btn btn-info" onclick = "edit_client(this)" >Edycja</button>
                                <button class="btn btn-danger" onclick = "edit_client(this)" >Wyłącz</button>
                            </td>
                            <td>
                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="client-wrapper">
    <div class="client-container">
        <div class="col-md-12">
            <div class="col-md-4">
                <label id="client_choice_name">Klient:</label>
                <label id="client_choice_name"> XYZ</label>
            </div>
            <div class="col-md-4">
                <label id="client_choice_name">Telefon:</label>
                <label id="client_choice_name"> 123654789</label>
            </div>
            <div class="col-md-4">
                <label id="client_choice_name">Typ:</label>
                <label id="client_choice_name"> Badania</label>
            </div>
        </div>
    </div>
</div>

{{--MODAL Dodaj Klienta--}}
<div id="Modal_Client" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_title">Dodaj nowego klienta<span id="modal_category"></span></h4>
            </div>
            <div class="modal-body">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Nowy Klient
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="myLabel">Nazwa Klienta</label>
                                    <input class="form-control" name="client_name" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="myLabel">Telefon kontaktowy</label>
                                    <input class="form-control" name="client_phone" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="myLabel">Typ</label>
                                    <select class="form-control" id="client_type">
                                        <option>Wybierz</option>
                                        <option>Badania</option>
                                        <option>Wysyłka</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-success form-control" id="save_client_modal" onclick = "save_client(this)" >Zapisz Klienta</button>
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



{{--@section('script')--}}
    {{--<script>--}}
       {{----}}
    {{--</script>--}}
{{--@endsection--}}

