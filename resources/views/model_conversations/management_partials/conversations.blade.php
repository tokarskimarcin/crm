<div id="menu2" class="tab-pane fade">
    <h3>Rozmowy</h3>
    <table class="table table-responsive table-condensed">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Rozmowa</th>
            <th>Trener</th>
            <th>Prezent</th>
            <th>Klient</th>
            <th>Kategoria</th>
            <th>Status</th>
            <th>Akcja</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr data-id="{{$item->id}}">
                <td>{{$item->name}}</td>
                <td>
                    <a data-toggle="modal" class="modal_trigger2" href="#play">
                        <span data-nameOfFile="{{$item->file_name}}" class="play-sound glyphicon glyphicon-play-circle"></span>
                    </a>
                </td>
                <td>{{$item->trainer}}</td>
                <td>{{$item->gift}}</td>
                <td>{{$item->client}}</td>
                <td>
                    <select class="form-control item_category" disabled>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if($category->id == $item->model_category_id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="item_status form-control" disabled>
                        <option value="1" @if($item->status == 1) selected @endif>Aktywna</option>
                        <option value="0" @if($item->status == 0) selected @endif>Nieaktywna</option>
                    </select>
                </td>
                <td>
                    @if($item->status == 1)
                        <button class=" btn btn-warning" data-type="items" data-action="1">Wyłącz</button>
                    @else
                        <button class="btn btn-success" data-type="items" data-action="2">Włącz </button>
                    @endif
                    <button class="btn btn-danger" data-type="items" data-action="0">Usuń</button>
                    <button class="btn btn-info" data-type="items" data-action="4" data-toggle="modal" data-target="#itemEdition">Edytuj</button>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td>Dodaj nową rozmowę</td>
            <td><button class="btn btn-info" data-type="items" data-action="5" data-toggle="modal" data-target="#itemEdition">Dodaj</button></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
    </table>
</div>