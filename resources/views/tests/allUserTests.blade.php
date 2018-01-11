@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Twoje testy</h1>
        </div>
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#waiting">Oczekujące</a></li>
    <li><a data-toggle="tab" href="#finished">Zakończone</a></li>
    <li><a data-toggle="tab" href="#judged">Ocenione</a></li>
</ul>

<div class="tab-content">
    <div id="waiting" class="tab-pane fade in active">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Data</th>
                        <th>Osoba testująca</th>
                        <th style="width: 10%">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>2017-12-12</td>
                        <td>Antoni Macierewicz</td>
                        <td><button class="btn btn-default">Szczegóły</button></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2017-12-12</td>
                        <td>Antoni Macierewicz</td>
                        <td><button class="btn btn-default">Szczegóły</button></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2017-12-12</td>
                        <td>Antoni Macierewicz</td>
                        <td><button class="btn btn-default">Szczegóły</button></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2017-12-12</td>
                        <td>Antoni Macierewicz</td>
                        <td><button class="btn btn-default">Szczegóły</button></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2017-12-12</td>
                        <td>Antoni Macierewicz</td>
                        <td><button class="btn btn-default">Szczegóły</button></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2017-12-12</td>
                        <td>Antoni Macierewicz</td>
                        <td><button class="btn btn-default">Szczegóły</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="finished" class="tab-pane fade">
    <div class="table-responsive" style="margin-top: 20px">
    <table class="table table-stripped">
        <thead>
            <tr>
                <th>Lp.</th>
                <th>Data</th>
                <th>Osoba testująca</th>
                <th style="width: 10%">Akcja</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
        </tbody>
    </table>
</div>
    </div>
    <div id="judged" class="tab-pane fade">
    <div class="table-responsive" style="margin-top: 20px">
    <table class="table table-stripped">
        <thead>
            <tr>
                <th>Lp.</th>
                <th>Data</th>
                <th>Osoba testująca</th>
                <th style="width: 10%">Akcja</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
            <tr>
                <td>1</td>
                <td>2017-12-12</td>
                <td>Antoni Macierewicz</td>
                <td><button class="btn btn-default">Szczegóły</button></td>
            </tr>
        </tbody>
    </table>
</div>
    </div>
</div>




@endsection

@section('script')
<script>

</script>
@endsection
