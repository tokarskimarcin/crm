@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <span id="page_title">Ocena pracowników IT</span>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Pracownik</th>
                        <th>Liczba ocen pozytywnych</th>
                        <th>Ocena jakości wykonania</th>
                        <th>Ocena kontaktu z serwisantem</th>
                        <th>Ocena czasu wykonania</th>
                        <th>Ocena ogólna</th>
                        <th>Średni czas realizacji</th>
                        <th>Oddzwonienia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($judge_results as $result)
                        <tr>
                            <td>{{$result->first_name . ' ' . $result->last_name}}</td>
                            <td>{{$result->user_sum_repaired}}/{{$result->user_sum}}</td>
                            <td>{{round($result->user_quality, 2)}}/6</td>
                            <td>{{round($result->user_contact, 2)}}/6</td>
                            <td>{{round($result->user_time, 2)}}/6</td>
                            <td>{{round($result->user_judge_sum, 2)}}/6</td>
                            <td>{{round($result->notifications_time_sum /3600, 2)}} h</td>
                            <td>{{$result->response_after}}/{{$result->user_sum}}</td>
                        </tr>
                    @endforeach
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
