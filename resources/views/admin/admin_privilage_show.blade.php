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
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Grupa panelowa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groups as $group)
                                            <tr>
                                                <td>{{$group->name}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <table class="table table-bordered">
                                    <thead>
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
                                            <td><button type="button" class="btn btn-info">Zobacz</button></td>
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

@include('workhours.registerHour');
@endsection

@section('script')

<script>


</script>
@endsection
