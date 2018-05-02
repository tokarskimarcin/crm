@extends('layouts.main')
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport Dzienny Telemarketing Gniezno</div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        @include('mail.otherCompanyMail.dayReportTelemarketing')
                                </div>
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
