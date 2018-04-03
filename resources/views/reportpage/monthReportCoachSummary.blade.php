@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Podsumowanie Trenerów - Raport Miesięczny/Tygodniowy</h1>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageMonthReportCoachSummary') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Oddział:</label>
                    <select class="form-control" name="dep_selected">
                        @foreach($departments as $key => $value)
                            <option @if($dep_id == $value->id) selected @endif value="{{$value->id}}">{{ $value->departments->name . ' ' . $value->department_type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Miesiąc:</label>
                    <select class="form-control" name="month_selected">
                        @foreach($months as $key => $value)
                            <option @if($month == $key) selected @endif value="{{$key}}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input style="margin-top: 25px; width: 100%" type="submit" class="btn btn-info" value="Generuj raport">
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="start_stop">
                            <div class="panel-body">
                                @include('mail.monthReportCoachSummary')
                            </div>
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

    </script>
@endsection
