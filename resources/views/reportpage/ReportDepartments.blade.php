@extends('layouts.main')
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Raport Miesięczny Oddziały</h1>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageReportDepartments') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Oddział:</label>
                    <select class="form-control" name="selected_dep">
                        <optgroup label="Oddziały">
                            @foreach($departments as $dep)
                                <option value="{{$dep->id}}" @if(($wiev_type == 'department') && $dep->id == $dep_id) selected @endif>{{$dep->departments->name . ' ' . $dep->department_type->name}}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Dyrektorzy">
                            @foreach($directors as $director)
                                <option
                                    @if($wiev_type == 'director' && ('10' . $director->id == $dep_id)) selected @endif
                                value="10{{ $director->id }}">{{ $director->last_name . ' ' . $director->first_name }}</option>
                            @endforeach
                        </optgroup>
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
                                @if($wiev_type == 'director')
                                    @if(Auth::user()->id == 4796)
                                        @include('mail.monthReportDirectors')
                                    @else
                                        Raport w przygotowaniu
                                    @endif
                                @else
                                    @include('mail.reportDepartments')
                                @endif
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
