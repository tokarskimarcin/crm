@extends('layouts.main')
@section('content')


{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Panel zarządzania</div>
            </div>
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
                            <div id="start_stop" class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="color: white; background-color: #666564;">
                                        <tr>
                                            <th>ID</th>
                                            <th>Grupa panelowa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groups as $group)
                                            <tr>
                                                <td>{{$group->id}}</td>
                                                <td>{{$group->name}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div id="start_stop" class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="color: white; background-color: #666564;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Oddziały Ogólne</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($departments as $item)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->name}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div id="start_stop" class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="color: white; background-color: #666564;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Dział</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($department_types as $item)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->name}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div id="start_stop" class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="color: white; background-color: #666564;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Oddział</th>
                                        <th>Dział</th>
                                        <th>Typ</th>
                                        <th>Prowizja średnia</th>
                                        <th>Prowizja godziny</th>
                                        <th>Prowizja PLN</th>
                                        <th>Cel(tygodniowy)</th>
                                        <th>Cel(weekend)</th>
                                        <th>Max % Janków</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($department_info as $item)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->department_name}}</td>
                                            <td>{{$item->department_type_name}}</td>
                                            <td>{{$item->type}}</td>
                                            <td>{{$item->commission_avg}}</td>
                                            <td>{{$item->commission_hour}}</td>
                                            <td>{{$item->commission_start_money}}</td>
                                            <td>{{$item->dep_aim}}</td>
                                            <td>{{$item->dep_aim_week}}</td>
                                            <td>{{$item->commission_janky}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>



                        <div class="col-lg-12">
                            <div class="form-group">
                                <input class="form-control" placeholder="Wyszukaj" id="search_link"/>
                            </div>
                            <div id="start_stop" class="table-responsive">
                                <table class="table table-bordered" id="to_search">
                                    <thead style="color: white; background-color: #666564;">
                                    <tr>
                                        <th>Nazwa</th>
                                        <th>Adres</th>
                                        <th>Grupa</th>
                                        <th>Uprawnienia</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($links as $link)
                                        <tr>
                                            <td>{{$link->name}}</td>
                                            <td>{{$link->link}}</td>
                                            <td>{{$link->link_groups_name}}</td>
                                            <td><a href={{url('admin_privilage_show/'.$link->id)}} class="btn btn-info" role="button">Zobacz</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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

$(document).ready(() => {
    $("#search_link").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#to_search tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

</script>
@endsection
