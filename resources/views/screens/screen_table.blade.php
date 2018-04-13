@extends('layouts.main')
@section('content')
    {{--************************************************************--}}
    {{--THIS VIEW SHOWS TABLE OF DEPARTMENTS AND RELATED TO IT LINKS--}}
    {{--************************************************************--}}

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Ekrany</div>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Oddział</th>
                <th>Linki</th>
            </tr>
        </thead>
        <tbody>

            @foreach($dane as $d)
            <tr>
                <td>{{$d->departments->name . " " . $d->department_type->name}}</td>
                <td><a href="{{URL::to("dept")}}/{{$d->id}}">Link </a></td>
            </tr>
            @endforeach

        </tbody>
    </table>

@endsection

@section('script')

    <script>
    </script>
@endsection
