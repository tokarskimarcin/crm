@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <button class="btn btn-success"> START WORK </button>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')

<script>
        $(".btn-success").click(function () {
            $.ajax({
                type: "POST",
                url: '{{ url('startWork') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    server = response;
                }
            });

        });

</script>
@endsection
