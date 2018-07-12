@extends('layouts.main')
@section('content')
<style>
    #start, #stop{
        width: 100%;
        height: 50px;
    }
    #done{
        width: 100%;
        height: 50px;
        background-color: #286090;
        color:white;
    }
</style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
    </div>
    {{--Informacje o zmianie hasła--}}
    @if (Session::has('message_ok'))
        <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
    @elseif(Session::has('message_nok'))
        <div class="alert alert-danger">{{ Session::get('message_nok') }}</div>
    @endif
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
                                            <div class="alert alert-danger" style="border: 1px solid #222;" role="alert">
                                               @if(Auth::user()->user_type_id == 1 ||  Auth::user()->user_type_id == 2)
                                                <strong>Podstawowa obsługa systemu!</strong></br></br>
                                                Przychodząc do pracy, logujemy się do systemu i używamy przycisku <b>Zaczynam Pracę</b>.<br><br>
                                                <b>Krok 2:</b>
                                                Kiedy zakończyliśmy swoją pracę, ponownie logujemy się do systemu i używamy przycisku <b>Kończę Pracę</b>.<br><br>
                                                <b>Krok 3:</b>
                                                Po zakończeniu pracy należy zarejestrować godzin pracy, używając przycisku <b>Rejestracja Godzin</b> <br/><br/>
                                                    <b>UWAGA!!! Jeśli nie zarejestrujesz godzin, trener nie dostanie informacji o twojej obecności w pracy i żadne godziny nie będą się liczyły do czasu pracy.</b><br>
                                                @else
                                                    <strong>UWAGA REJESTRACJA CZASU PRACY!</strong></br></br>
                                                    Pamiętaj o zarejestrowaniu swojej obecności w pracy przez <b>Zaczynam Pracę</b>/<b>Kończę Pracę</b><br><br>

                                                    Po zakończeniu pracy należy zarejestrować godzin pracy, używając przycisku <b>Rejestracja Godzin</b>
                                                @endif
                                            </div>
                                            <!--Ładowanie przycisku start stop do div  -->
                                            <div id="startstopdiv"></div>
                                    </div>

                                    <?php if($status == 0): ?>
                                    <button id="start" class="btn btn-success"> Zaczynam pracę </button>
                                    <?php elseif($status == 1): ?>
                                    <button id="stop" class="btn btn-danger"> Kończę Pracę </button>
                                    <?php elseif($status == 2): ?>
                                    <button id="done" class="btn btn-info" data-toggle="modal" data-target="#registerModal">Rejestracja Godzin</button>
                                     <?php elseif($status >=3): ?>
                                       <div class="alert alert-success">
                                           Godziny zostały zarejestrowane w przedziale: <span id="register_hour_done_start">{{substr($register_start,0,-3)}}</span> - <span id="register_hour_done_stop">{{substr($register_stop,0,-3)}}</span>
                                       </div>
                                    <button id="done" class="btn btn-info" data-toggle="modal" data-target="#registerModal">Edytuj godziny pracy</button>
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

      swal({
        title: 'Zakończyć pracę?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak',
        animation: false
      }).then((result) => {
        if (result.value) {
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
                  $("#stop").text('Rejestracja Godzin');
                  $("#stop").attr('id', 'done');
                  $("#done").removeClass('btn-danger');
                  $("#done").addClass('btn-default');
              }
          });
          swal(
            'Sukces',
            'Zakończyłeś pracę!',
            'success'
          )
        }
      })


    });

</script>
@endsection
