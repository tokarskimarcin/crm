@extends('layouts.main')
@section('content')

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Panel zarządzania</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Status Pracy
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        <div class="well">
                                            <div class="alert alert-danger" style="border: 1px solid #222;" role="alert">
                                                <strong>Podstawowa obsługa systemu!</strong></br>
                                                Witaj <b></b>!</br></br>

                                                <b>Krok 1:</b><br>
                                                Przychodząc do pracy, logujemy się do systemu i używamy przycisku <b>Zaczynam Pracę</b>.<br><br>
                                                <b>Krok 2:</b><br>
                                                Kiedy zakończyliśmy swoją pracę, ponownie logujemy się do systemu i używamy przycisku <b>Kończę Pracę</b>.<br><br>
                                                <b>Krok 3:</b><br>
                                                Po zakończeniu pracy należy koniecznie przejść do zakładki <b>Rejestracja Godzin</b> i zarejestrować godziny pracy.<br/><br/><b>UWAGA!!! Jeśli nie zarejestrujesz godzin, trener nie dostanie informacji o twojej obecności w pracy i żadne godziny nie będą się liczyły do czasu pracy.</b><br>
                                            </div>
                                            <!--Ładowanie przycisku start stop do div  -->
                                            <div id="startstopdiv"></div>
                                        </div>
                                    </div>

                                    <?php if($status == 0): ?>
                                    <button id="start" class="button btn-success"> Zacznij Pracę </button>
                                    <?php elseif($status == 1): ?>
                                    <button id="stop" class="button btn-danger"> Zakończ Pracę </button>
                                    <?php elseif($status == 2): ?>
                                    <button id="done" class="button" data-toggle="modal" data-target="#registerModal">Rarejestruj Godziny</button>
                                     <?php elseif($status >=3): ?>
                                       <div class="alert alert-success">
                                           Godziny zostały zarejestrowane w przedziale: <span id="register_hour_done_start">{{substr($register_start,0,-3)}}</span> - <span id="register_hour_done_stop">{{substr($register_stop,0,-3)}}</span>
                                       </div>
                                    <button id="done" class="button" data-toggle="modal" data-target="#registerModal">Edytuj godziny pracy</button>
                                    <?php endif?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('workhours.registerHour')
@endsection

@section('script')

<script>
    var $status_work = <?php echo $status ?>;
    $("#start_stop").on('click', '#start',function () {
            $.ajax({
                type: "POST",
                url: '{{ url('startWork') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    server = response;
                    $("#start").text('Zakończ pracę');
                    $("#start").attr('id', 'stop');
                    $("#stop").removeClass('btn-success');
                    $("#stop").addClass('btn-danger');
                }
            });
        });
    $("#start_stop").on('click', '#stop',function () {

        var stop_conf = confirm("Czy napewno chcesz zakończyć pracę?");

        if (stop_conf == true) {
            $.ajax({
                type: "POST",
                url: '{{ url('stopWork') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    server = response;
                    $("#stop").attr('data-toggle','modal');
                    $("#stop").attr('data-target','#registerModal');
                    $("#stop").text('Rejestruj godziny');
                    $("#stop").attr('id', 'done');
                    $("#done").removeClass('btn-danger');
                    $("#done").addClass('btn-default');
                }
            });
        }

    });

</script>
@endsection
