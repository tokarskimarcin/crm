@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Testy / Wszystkie testy</div>
        </div>
    </div>
<div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped thead-inverse">
                <thead>
                    <tr>
                        <th style="width: 5%">Lp.</th>
                        <th>Nazwa testu</th>
                        <th style="width: 10%">Osoba testująca</th>
                        <th style="width: 10%">Osoba testowana</th>
                        <th style="width: 10%">Data</th>
                        <th style="width: 10%">Sprawdź test</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($tests as $test)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$test->name}}</td>
                            <td>{{$test->cadre->first_name . ' ' . $test->cadre->last_name}}</td>
                            <td>{{$test->user->first_name . ' ' . $test->user->last_name}}</td>
                            <td>{{substr($test->created_at, 0, 10)}}</td>
                            <td>
                                <a class="btn btn-default" href="{{URL::to('/check_test')}}/{{$test->id}}">
                                    <span style="color:green" class="glyphicon glyphicon-education"></span> Sprawdź test
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer"></div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')
<script>

</script>
@endsection
