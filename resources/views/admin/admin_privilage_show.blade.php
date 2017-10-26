@extends('layouts.main')
@section('content')


{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Panel zarządzania</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Uprawnienia grup i użytkowników
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div id="start_stop">

                                </table>
                            </div>
                        </div>
                        <form class="form-horizontal" method="post" action="/admin_privilage_edit/{{$link_info->id}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Nazwa</th>
                                        <th>Adres</th>
                                        <th>Grupa</th>
                                        <th>Uprawnienia</th>
                                        <th>Akcja</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" id="name" name="link_name" value='{{$link_info->name}}'></td>
                                            <td><input type="text" class="form-control" id="link" name="link_adress" value={{$link_info->link}}></td>
                                            <td><select class="form-control" id="sel1" name="link_group">
                                                    @foreach($groups as $group)
                                                        @if($link_info->group_link_id == $group->id)
                                                            <option value={{$group->id}} selected>{{$group->name}}</option>
                                                        @else
                                                            <option value={{$group->id}}>{{$group->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="selectpicker" name="link_privilages[]" title="Brak przypisanych uprawnień" multiple data-actions-box="true">
                                                    @foreach($users_type as $item_user_type)
                                                        {{$isSelectet = 0}}
                                                        @foreach($link as $item_link)
                                                            @if($item_link->relation_user_type_id == $item_user_type->id)
                                                               {{$isSelectet++}}
                                                            @endif
                                                        @endforeach
                                                            @if($isSelectet!=0)
                                                                <option value={{$item_user_type->id}} selected >{{$item_user_type->name}}</option>
                                                            @else
                                                                <option value={{$item_user_type->id}}>{{$item_user_type->name}}</option>
                                                            @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="submit" name="admin_privilage_edit" id="admin_privilage_edit" class="btn btn-primary" style="font-size:18px; width:100%;" value="Zapisz"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('workhours.registerHour');
@endsection

@section('script')

<script>

    $('.selectpicker').selectpicker({
        selectAllText: 'Zaznacz wszystkie',
        deselectAllText: 'Odznacz wszystkie'
    });




</script>
@endsection
