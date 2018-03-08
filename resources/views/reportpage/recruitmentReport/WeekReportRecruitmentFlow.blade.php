@extends('layouts.main')
@section('content')

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tygodniowy Raport Spływu Rekrutacji</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        @include('mail.recruitmentMail.weekReportRecruitmentFlow')
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
