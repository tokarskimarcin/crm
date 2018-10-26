<div id="menu1" class="tab-pane fade">
    <h3>Kategorie</h3>

    <table class="table table-stripped table-condensed">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Zdjęcie</th>
            <th>Status</th>
            <th>Podkategoria</th>
            <th>Akcja</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
        <tr data-id="{{$category->id}}">
            <td>{{$category->id}}</td>
            <td>{{$category->name}}</td>
            <td>{{$category->img}} <a href="{{asset('storage/')}}/{{$category->img}}"><span class="glyphicon glyphicon-picture"></span></a></td>
            <td>
                <select class="category_status form-control" disabled>
                    <option value="1" @if($category->status == 1) selected @endif>Aktywna</option>
                    <option value="0" @if($category->status == 0) selected @endif>Nieaktywna</option>
                </select>
            <td>
                <select class="category_subcategory form-control" disabled>
                    <option> Wybierz</option>
                    <option value="0" @if($category->subcategory_id == 0) selected @endif>Główna</option>
                    @foreach($categories as $category2)
                    <option value="{{$category2->id}}" @if($category->subcategory_id == $category2->id) selected @endif>{{$category2->name}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                @if($category->status == 1)
                <button class=" btn btn-warning" data-type="category" data-action="1">Wyłącz</button>
                @else
                <button class="btn btn-success" data-type="category" data-action="2">Włącz </button>
                @endif
                <button class="btn btn-danger" data-type="category" data-action="0">Usuń</button>
                <button class="btn btn-info" id="edit" data-type="category" data-action="4" data-toggle="modal" data-target="#myModal">Edytuj</button>
            </td>
        </tr>
        @endforeach
        <tr>
            <td>Dodaj nową kategorie</td>
            <td><button class="btn btn-info" id="edit" data-type="category" data-action="5" data-toggle="modal" data-target="#myModal">Dodaj</button></td>
        </tr>
        </tbody>
    </table>
</div>