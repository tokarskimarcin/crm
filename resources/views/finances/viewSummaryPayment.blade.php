@extends('layouts.main')
@section('style')
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
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
@section('content')


{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Podgląd Wypłat</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Podsumowanie Wypłat
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        <div class="well">
                                            <form action="view_summary_payment" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-8">
                                                    <select name="search_summary_money_month" class="form-control" style="font-size:18px;">
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

                                            @if(isset($summary_month))
                                                <table class="table table-striped table-bordered dt-responsive nowrap"cellspacing="0"  width="100%" id="datatable">
                                                    <thead>
                                                    <tr>
                                                        <th>Lp.</th>
                                                        <th>Oddział/Dział</th>
                                                        <th>Suma Wypłat</th>
                                                        <th>Liczba Godzin</th>
                                                        <th>Śr. Stawka</th>
                                                        <th>Liczba Dok.</th>
                                                        <th>Liczba Stu.</th>
                                                        <th>Liczba pracowników</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $total_payment = 0;
                                                        $total_hours = 0;
                                                        $avg_per_hour = 0;
                                                        $total_documents = 0;
                                                        $total_students = 0;
                                                        $total_employee = 0;
                                                    @endphp
                                                @foreach($summary_month as $item)
                                                    @php
                                                        $total_payment += $item->payment;
                                                        $total_hours += $item->hours;
                                                        if($item->hours == 0)
                                                            $avg_per_hour=0;
                                                        else
                                                            $avg_per_hour = $item->payment/($item->hours/3600);
                                                        $total_documents += $item->documents;
                                                        $total_students += $item->students;
                                                        $total_employee += $item->employee_count;
                                                    @endphp
                                                    <tr>
                                                        <td>1</td>
                                                        <td>{{$item->department_info_id}}</td>
                                                        <td>{{$item->payment}} PLN</td>
                                                        <td>{{round($item->hours/3600,2)}}</td>
                                                        <td>{{round($avg_per_hour,2)}}</td>
                                                        <td>{{$item->documents}}</td>
                                                        <td>{{$item->students}}</td>
                                                        <td>{{$item->employee_count}}</td>
                                                    </tr>
                                                @endforeach
                                                    @php
                                                        if($total_hours == 0)
                                                            $total_avg_per_hour = 0;
                                                        else
                                                            $total_avg_per_hour = $total_payment/($total_hours/3600);
                                                    @endphp
                                                    <tr>
                                                        <td colspan="2"><b>Total</b></td>
                                                        <td>{{$total_payment}}</td>
                                                        <td>{{round($total_hours/3600,2)}}</td>
                                                        <td>{{round($total_avg_per_hour,2)}}</td>
                                                        <td>{{$total_documents}}</td>
                                                        <td>{{$total_students}}</td>
                                                        <td>{{$total_employee}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @endif
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
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
