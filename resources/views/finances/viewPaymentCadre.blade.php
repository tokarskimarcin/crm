@extends('layouts.main')
@section('style')
    <link href="{{ asset('/css/buttons.dataTables.min.css')}}" rel="stylesheet">
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
            <div class="alert gray-nav ">Rozliczenia / Podgląd Wypłat Kadra</div>
        </div>
    </div>
</div>


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
                                            <form action="view_payment_cadre" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-8">
                                                    <select name="search_money_month" class="form-control" style="font-size:18px;">
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
                                    </div>
                                                @if(isset($month))
                                                            @php
                                                                $payment_total = 0;
                                                                $rbh_total = 0;
                                                                $documents_total = 0;
                                                                $students_total = 0;
                                                                $users_total = 0;
                                                            @endphp
                                                            @php $wrapper_scroll = 0;  @endphp
                                                            @foreach($salary as $item => $key)
                                                                @foreach($agencies as $agency)
                                                                    @if($agency->id == $item)
                                                                        @php
                                                                            $salary_total_all = 0;
                                                                            $row_number = 1;@endphp

                                                                    {{--Typ Umowy--}}
                                                                    <div>
                                                                        <h4 style="margin-top:20px;"><b>Tabela Wypłat - {{$agency->name}}:</b></h4>
                                                                    </div>
                                                                    <br />
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body">
                                                                      <div class="wrapper{{++$wrapper_scroll}}">
                                                                      <div class="div{{$wrapper_scroll}}"></div>
                                                                      </div>
                                                                      <div class="wrapper{{++$wrapper_scroll}}">
                                                                        <div class="div{{$wrapper_scroll}}">
                                                                        <table class="table table-striped table-bordered dt-responsive nowrap"cellspacing="0"  width="100%" id="datatable{{$agency->id}}">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Lp.</th>
                                                                                <th>Imię</th>
                                                                                <th>Nazwisko</th>
                                                                                <th>Podstawa</th>
                                                                                <th>Dodatek</th>
                                                                                <th>Premia</th>
                                                                                <th>Kara</th>
                                                                                <th>Student</th>
                                                                                <th>Dokument</th>
                                                                                <th>Wynagrodzenie</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                        @foreach($key as $item2)
                                                                            @php
                                                                                    $student = ($item2->student == 0) ? "Nie" : "Tak";
                                                                                    $documents = ($item2->documents == 0) ? "Nie" : "Tak";
                                                                                    $salary = ($item2->salary == null) ? 0 : $item2->salary;
                                                                                    $additional_salary = ($item2->additional_salary == null) ? 0 : $item2->additional_salary;
                                                                                    $bonus = ($item2->bonus == null) ? 0 : $item2->bonus;
                                                                                    $penatly = ($item2->penalty == null) ? 0 : $item2->penalty;
                                                                                    $total_one_salary = $salary+$additional_salary+$bonus-$penatly;
                                                                                    if($total_one_salary < 0)
                                                                                        $total_one_salary = 0;
                                                                                    $salary_total_all+=$total_one_salary;
                                                                                    $payment_total=+$salary_total_all;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{$row_number++}}</td>
                                                                                <td>{{($item2->first_name)}}</td>
                                                                                <td>{{($item2->last_name)}}</td>
                                                                                <td>{{$salary}} PLN</td>
                                                                                <td>{{$additional_salary}} PLN</td>
                                                                                <td>{{$bonus}} PLN</td>
                                                                                <td>{{$penatly*(-1)}} PLN</td>
                                                                                <td>{{$student}}</td>
                                                                                <td>{{$documents}}</td>
                                                                                <td>{{$total_one_salary}} PLN</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        <tr>
                                                                            <td colspan="8"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td style="display: none;"></td>
                                                                            <td> Suma:</td>
                                                                            <td>{{$salary_total_all}} PLN</td>

                                                                        </tr>
                                                                            </tbody>
                                                                        </table>
                                                                      </div>
                                                                    </div>
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
        print_R($payment_total);
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
                      console.log(rbh_total);
                    }
                });

            </script>
        @endif
    @endif

<script>
    $(document).ready(function() {
        $('thead > tr> th').css({ 'min-width': '1px', 'max-width': '100px' });
        table = $('#datatable1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Umowy Szkoleniowe'
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
                    title: 'APT Job Center Service'
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
                    title: 'Fruit Garden'
                }
            ],
            "autoWidth": false,
            "searching": false,
            "ordering": false,
            "paging": false,
            "bInfo": false,
        });
    });

</script>
@endsection
