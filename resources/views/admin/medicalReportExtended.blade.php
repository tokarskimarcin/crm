@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        font-size: 20px;
        color: #aaa;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Rozliczenia / Raport pakiety medyczne rozszerzony</div>
        </div>
    </div>
</div>

<div class="row">
    <form method="POST" action="{{ URL::to('/medicalPackagesRaportExtended') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-4">
            <div class="form-group">
                <label class="myLabel">Rok:</label>
                <select class="form-control" name="year">
                    <option value="2018">2018</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="myLabel">Miesiąc:</label>
                <select class="form-control" name="month">
                    @foreach($months as $month)
                        <option @if($selected_month == $month['id']) selected @endif value="{{$month['id']}}">{{$month['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <input style="width: 100%; margin-top: 33px;" type="submit" class="btn btn-info" value="Wybierz">
            </div>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped thead-inverse">
                <thead>
                    <tr>
                        <th>Oddział</th>
                        <th>Ilość konsultantów</th>
                        <th>Ilość pracowników kadry</th>
                        <th>Suma pracowników</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_consultant = 0;
                        $total_cadre = 0;
                    @endphp
                    @foreach($packages as $package)
                        @php
                            $total_consultant += $package->consultant_sum;
                            $total_cadre += $package->cadre_sum;
                        @endphp
                        <tr>
                            <td>{{$package->dep_name . ' '. $package->dep_name_type}}</td>
                            <td>{{$package->consultant_sum}}</td>
                            <td>{{$package->cadre_sum}}</td>
                            <td>{{$package->total_sum}}</td>
                        </tr>
                    @endforeach
                    <tr class="danger">
                        <td><b>SUMA</b></td>
                        <td><b>{{$total_consultant}}</b></td>
                        <td><b>{{$total_cadre}}</b></td>
                        <td><b>{{$total_cadre + $total_consultant}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>

    </script>
@endsection
