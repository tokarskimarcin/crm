@extends('layouts.main')
@section('content')
    <style>
        .second_row {
            overflow-x: scroll;
        }
    </style>
    {{--Header page --}}
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <div class="alert gray-nav">Raport Miesięczny Oddziały</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info"> <strong>Raport Miesięczny Oddziały - zestawienie statystyk dotyczących wszystkich oddziałów w zeszłym miesiącu, podzielonych na tygodnie. </strong></br>
                    <div class="additional_info">
                        RBH Plan = Roboczogodziny w grafiku </br>
                        RBH Real = Roboczogodziny zaakceptowane </br>
                        Średnia = Zgody / RBH real </br>
                        % janków = 100% * liczba janków / liczba odsłuchanych </br>
                        % celu = 100% * zgody / cel zgody </br>
                        Cel RBH = cel zgody / średnia na projekcie</br>
                        Grafik Real = 100% * RBH Real / Cel RBH (procent zaakceptowanych RBH względem zaplanowanych RBH)

                    </div>
                </div>
                <div class="alert alert-warning">Aby uzyskać możliwie najlepszy podgląd raportu zaleca się wyłączenie panelu nawigacyjnego</br> naciskając przycisk OFF w górnym lewym rogu dokumentu. </br> Raport można przewijać horyzontalnie!</div>
            </div>
        </div>

        <div class="row second_row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                    @include('mail.summaryReportDepartment')
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
