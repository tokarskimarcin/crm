@extends('layouts.main')
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Raport Podważonych Janków</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
                <div class="row">
                            <div class="panel-body" style="font-size: 12px;">
                                @include('mail.weekReportJanky')
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
