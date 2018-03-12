@extends('layouts.main')
@section('style')
    <link href="{{ asset('/css//buttons.dataTables.min.css')}}" rel="stylesheet">
    <style>
        button{
            width: 100%;
            height: 50px;
        }
        div.container {
            width: 80%;
        }
        table.table {
            /*clear: both;*/
            /*margin-bottom: 6px !important;*/
            /*max-width: none !important;*/
            /*table-layout: fixed;*/
            /*word-break: break-all;*/
        }
    </style>
@endsection
@section('content')


{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Rozliczenia / Podgląd Wypłat</div>
        </div>
    </div>
</div>

{{--Pomocnicza zmienna do przekzywanie informacji czy dane wypłaty można pobrać do csv--}}
@php
    $payment_saved_pom = 0;
@endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Podgląd Wypłat
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        <div class="well">
                                            <form action="view_payment" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-8">
                                                    <select id="month_select" name="search_money_month" class="form-control" style="font-size:18px;">
                                                        @for ($i=0; $i < 3; $i++)
                                                            @php
                                                            $date = date("Y-m", mktime(0, 0, 0, date("m")-$i, 1, date("Y")));
                                                            @endphp
                                                            @if (isset($month))
                                                                @if ($month == $date)
                                                                    <option selected>{{$date}}</option>
                                                                @else{
                                                                    <option>{{$date}}</option>
                                                                @endif
                                                            @else
                                                                <option>{{$date}}</option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary" id="show_load_data_info" style="width:100%;">Wyświetl</button>
                                                </div></br></br>
                                            </form>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                Legenda
                                            </div>
                                            <div class="panel-body">
                                                <div class="alert alert-success">
                                                    <h1>Wypłaty liczone są wg następujacego schematu:</h1>
                                                    <h3>
                                                        Podstawa wypłaty jest nienaruszalna - w przypadku kary przekraczających premię, wszystkie kary są zerowane, a pracownik dostaje wypracowaną podstawę.
                                                    </h3>
                                                    <h3>
                                                        W każdym innym przypadku suma kar odejmowana jest od sumy premii, a suma wypłaty dla danego pracownika to podstawa + pozostała premia.
                                                    </h3>
                                                    <h2>Wypłaty należy zaakceptować, klikając przycisk "Zaakceptuj wypłaty"</h2>
                                                    <h3>
                                                        Zaakceptowanie wypłat jest wiążące, należy wykonać tę czynność do 3 dnia każdego miesiąca.
                                                        Brak akceptacji wypłat uniemożliwi ich wygenerowanie.
                                                    </h3>
                                                </div>
                                            </div>
                                            @if(isset($month))
                                                @if(!$payment_saved->isNotEmpty())
                                                    <button class="btn btn-danger" id="accept_payment">Zaakceptuj wypłaty</button>
                                                    @php $payment_saved_pom = 0 @endphp
                                                @else
                                                    @php $payment_saved_pom = 1 @endphp
                                                    <button class="btn btn-success">Wypłaty zaakceptowane</button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                                @if(isset($month))
                                                            @php
                                                                $payment_total = 0;
                                                                $rbh_total = 0;
                                                                $documents_total = 0;
                                                                $students_total = 0;
                                                                $users_total = 0;
                                                            @endphp
                                                            @foreach($salary as $item => $key)
                                                                @foreach($agencies as $agency)
                                                                    @if($agency->id == $item)
                                                                        @php $salary_total_all = 0; $row_number = 1;@endphp

                                                                    {{--Typ Umowy--}}
                                                                <div style="float:left;">
                                                                    <h4 style="margin-top:20px;"><b>Tabela Wypłat - {{$agency->name}}:</b></h4>
                                                                </div>
                                                                <br />
                                                                <div class="panel panel-default">
                                                                    <div style="margin-top: 18px;" class="panel-body">
                                                                        <div class="table-responsive">
                                                                        <table class="table table-striped table-bordered dt-responsive nowrap"cellspacing="0"  width="100%" id="datatable{{$agency->id}}">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Lp.</th>
                                                                                <th>Imię</th>
                                                                                <th>Nazwisko</th>
                                                                                <th>Login Godzinówki</th>
                                                                                <th>Stawka</th>
                                                                                <th>RBH</th>
                                                                                <th>Podstawa</th>
                                                                                <th>Premia - Kara</th>
                                                                                <th>Student</th>
                                                                                <th>Dokumenty</th>
                                                                                <th>Całość na konto</th>
                                                                                <th>Wypłata</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                        @foreach($key as $item2)
                                                                          @if($item2->sum > 0)
                                                                            @php // set variable
                                                                                $avg = 0;
                                                                                $rbh = 0;
                                                                                $janky_proc = 0;
                                                                                $standart_salary = 0;
                                                                                $bonus_penalty = 0;
                                                                                $salary_total = 0;
                                                                                $rbh = round($item2->sum/3600,2);
                                                                                $janky_cost_per_price = 0;
                                                                                $users_total++;
                                                                                if($item2->success == 0)
                                                                                    $avg = 0;
                                                                                 else
                                                                                    $avg = round($item2->success/($item2->sum/3600),2);
                                                                                if($item2->ods == 0)
                                                                                    $janky_proc = 0;
                                                                                else
                                                                                    $janky_proc = round(($item2->janki*100)/$item2->ods ,2);

                                                                                /* system liczenia janków
                                                                                foreach ($janky_system as $system_item)
                                                                                {
                                                                                   $system_item->max_proc;
                                                                                   if($janky_proc >= $system_item->min_proc && $janky_proc < $system_item->max_proc)
                                                                                   {
                                                                                        $janky_cost_per_price = $system_item->cost;
                                                                                   }
                                                                                }
                                                                                */
                                                                                //$janky_cost = $item2->janki * $janky_cost_per_price;
                                                                                $standart_salary = round($rbh * $item2->rate,2);
                                                                                $bonus_penalty = $item2->premia - $item2->kara;
                                                                                $student = ($item2->student == 0) ? "Nie" : "Tak";
                                                                                $documents = ($item2->documents == 0) ? "Nie" : "Tak";
                                                                                if ($bonus_penalty < 0) {
                                                                                    $bonus_penalty = 0;
                                                                                }
                                                                                $salary_total = $standart_salary+$bonus_penalty;//-$janky_cost;
                                                                                if($salary_total < 0 )
                                                                                {
                                                                                    $salary_total = 0;
                                                                                }
                                                                                $salary_total_all += $salary_total;

                                                                                $documents_total += $item2->documents;
                                                                                $students_total +=$item2->student;
                                                                                $rbh_total += $item2->sum;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{$row_number++}}</td>
                                                                                <td>{{($item2->first_name)}}</td>
                                                                                <td>{{($item2->last_name)}}</td>
                                                                                <td>{{($item2->username)}}</td>
                                                                                <td>{{($item2->rate)}}</td>
                                                                                <td>{{$rbh}}</td>
                                                                                {{--<td>{{($janky_proc)}} %</td>--}}
                                                                                {{--<td>{{($janky_cost*(-1))}} PLN</td>--}}
                                                                                <td>{{($standart_salary)}}</td>
                                                                                <td>{{($bonus_penalty)}}</td>
                                                                                <td>{{($student)}}</td>
                                                                                <td>{{($documents)}}</td>
                                                                                <td>{{(($item2->salary_to_account == 0) ? "Nie" : "Tak")}}</td>
                                                                                <td>{{(round($salary_total,2))}}</td>
                                                                            </tr>
                                                                            @endif
                                                                        @endforeach

                                                                        @php
                                                                            $payment_total += $salary_total_all;
                                                                        @endphp
                                                                        <tr>
                                                                            <td colspan="10"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td> Suma:</td>
                                                                            <td>{{round($salary_total_all,2)}} PLN</td>
                                                                        </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                @endforeach
                                                            @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('/js/jszip.min.js')}}"></script>
    <script src="{{ asset('/js/buttons.html5.min.js')}}"></script>

@if(isset($payment_total))
    @if($payment_total !=0)
        <script>
            var payment_total =  <?php echo json_encode($payment_total); ?>;
            var documents_total = <?php echo json_encode($documents_total); ?>;
            var students_total = <?php echo json_encode($students_total); ?>;
            var rbh_total = <?php echo json_encode($rbh_total); ?>;
            var month = <?php echo json_encode($month); ?>;
            var user_total = <?php echo json_encode($users_total); ?>;
            $.ajax({
                type: "POST",
                url: '{{ route('api.summary_payment_save') }}',
                data: {"payment_total": payment_total, "documents_total": documents_total,
                    "students_total": students_total,"rbh_total":rbh_total,"month":month,
                    "user_total":user_total},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                }
            });

         </script>
     @endif
@endif
<script>

    $(document).ready(function() {
        $('#accept_payment').on('click',function (e) {
            console.log(123);
            swal({
                title: 'Jesteś pewien?',
                text: "Spowoduje to zaakceptowanie wypłat, bez możliwości cofnięcia zmian!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Zaakceptuj'
            }).then((result) => {
                if (result.value)
            {
                $.ajax({
                    type:"POST",
                    url: '{{route('api.paymentStory')}}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        accetp_month: $('#month_select').val()
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            }
        });
        });



        $('thead > tr> th').css({ 'min-width': '1px', 'max-width': '100px' });
        table = $('#datatable1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Umowy Szkoleniowe',
                    customize: function( xlsx ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c', sheet).attr('s', '25');
                        $('row c[r^="A"]', sheet).attr( 's', '30' );
                        $('row c[r^="B"]', sheet).attr( 's', '30' );
                        $('row c[r^="C"]', sheet).attr( 's', '30' );
                        $('row c[r^="D"]', sheet).attr( 's', '30' );
                        $('row c[r^="H"]', sheet).each( function () {
                            if($('is t', this).text() != 'Kara(Janki)')
                            {
                                $text = $('is t', this).text();
                                var penatly_bonus = $text.split(" ");
                                if(penatly_bonus[0] < 0)
                                {
                                    $(this).attr( 's', '35' );
                                }
                            }

                        });
                        $('row c[r^="I"]', sheet).each( function () {
                            if($('is t', this).text() != 'Premia - Kara')
                            {
                                $text = $('is t', this).text();
                                var penatly_bonus = $text.split(" ");
                                if(penatly_bonus[0] < 0)
                                {
                                    $(this).attr( 's', '35' );
                                }
                            }
                        });
                        $('row c[r^="K"]', sheet).each( function (key,value) {
                            if($('is t', this).text() != 'Całość na konto')
                            {
                                $text = $('is t', this).context.textContent;

                                if($text == 'Tak')
                                {
                                    let row_number = $('is t', this).context.attributes[1].nodeValue;
                                    row_number = row_number.substring(1);
                                    $('row:nth-child('+row_number+') c', sheet).attr( 's', '45' );
                                }
                            }
                        });
                        $('row:nth-child(2) c', sheet).attr( 's', '42' );
                        $('row:first c', sheet).attr( 's', '51','1','2' );
                        $('row:last c', sheet).attr( 's', '2' );

                    }
                }
            ],
            responsive: true,
            "autoWidth": false,
            "searching": false,
            "ordering": false,
            "paging": false,
            "bInfo": false,
        });
        table2 = $('#datatable2').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'APT Job Center Service',
                    customize: function( xlsx ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c', sheet).attr('s', '25');
                        $('row c[r^="A"]', sheet).attr( 's', '30' );
                        $('row c[r^="B"]', sheet).attr( 's', '30' );
                        $('row c[r^="C"]', sheet).attr( 's', '30' );
                        $('row c[r^="D"]', sheet).attr( 's', '30' );
                        $('row c[r^="H"]', sheet).each( function () {
                            if($('is t', this).text() != 'Kara(Janki)')
                            {
                                $text = $('is t', this).text();
                                var penatly_bonus = $text.split(" ");
                                if(penatly_bonus[0] < 0)
                                {
                                    $(this).attr( 's', '35' );
                                }
                            }

                        });
                        $('row c[r^="I"]', sheet).each( function () {
                            if($('is t', this).text() != 'Premia - Kara')
                            {
                                $text = $('is t', this).text();
                                var penatly_bonus = $text.split(" ");
                                if(penatly_bonus[0] < 0)
                                {
                                    $(this).attr( 's', '35' );
                                }
                            }
                        });
                        $('row c[r^="K"]', sheet).each( function (key,value) {
                            if($('is t', this).text() != 'Całość na konto')
                            {
                                $text = $('is t', this).context.textContent;

                                if($text == 'Tak')
                                {
                                    let row_number = $('is t', this).context.attributes[1].nodeValue;
                                    row_number = row_number.substring(1);
                                    $('row:nth-child('+row_number+') c', sheet).attr( 's', '45' );
                                }
                            }
                        });
                        $('row:nth-child(2) c', sheet).attr( 's', '42' );
                        $('row:first c', sheet).attr( 's', '51','1','2' );
                        $('row:last c', sheet).attr( 's', '2' );

                    }
                }
            ],
            "autoWidth": false,
            "searching": false,
            "ordering": false,
            "paging": false,
            "bInfo": false,
        });
        table3 = $('#datatable3').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Fruit Garden',
                    customize: function( xlsx ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c', sheet).attr('s', '25');
                        $('row c[r^="A"]', sheet).attr( 's', '30' );
                        $('row c[r^="B"]', sheet).attr( 's', '30' );
                        $('row c[r^="C"]', sheet).attr( 's', '30' );
                        $('row c[r^="D"]', sheet).attr( 's', '30' );
                        $('row c[r^="H"]', sheet).each( function () {
                            if($('is t', this).text() != 'Kara(Janki)')
                            {
                                $text = $('is t', this).text();
                                var penatly_bonus = $text.split(" ");
                                if(penatly_bonus[0] < 0)
                                {
                                    $(this).attr( 's', '35' );
                                }
                            }

                        });
                        $('row c[r^="I"]', sheet).each( function () {
                            if($('is t', this).text() != 'Premia - Kara')
                            {
                                $text = $('is t', this).text();
                                var penatly_bonus = $text.split(" ");
                                if(penatly_bonus[0] < 0)
                                {
                                    $(this).attr( 's', '35' );
                                }
                            }
                        });
                        $('row c[r^="K"]', sheet).each( function (key,value) {
                            if($('is t', this).text() != 'Całość na konto')
                            {
                                $text = $('is t', this).context.textContent;

                                if($text == 'Tak')
                                {
                                    let row_number = $('is t', this).context.attributes[1].nodeValue;
                                    row_number = row_number.substring(1);
                                    $('row:nth-child('+row_number+') c', sheet).attr( 's', '45' );
                                }
                            }
                        });
                        $('row:nth-child(2) c', sheet).attr( 's', '42' );
                        $('row:first c', sheet).attr( 's', '51','1','2' );
                        $('row:last c', sheet).attr( 's', '2' );

                    }
                }
            ],
            "autoWidth": false,
            "searching": false,
            "ordering": false,
            "paging": false,
            "bInfo": false,
        });

        // Ukrycie klawisza pozwalającego wygenerować wypłatę w csv
        var payment_saved = '{{$payment_saved_pom}}';
        if(payment_saved == 0){
            $(".buttons-html5").css('display','none');
        }
    });



</script>
@endsection
