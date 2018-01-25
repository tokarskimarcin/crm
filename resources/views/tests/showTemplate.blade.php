@extends('layouts.main')
@section('content')
<style>
    .xsm-col-th {
        width: 5%
    }
    .md-col-th {
        width: 10%
    }
    .sm-col-th {
        width: 15%
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Testy / Gotowe Szablony</div>
        </div>
    </div>
</div>
@if (Session::has('message_ok'))
    <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif
@if (Session::has('message_delete'))
    <div class="alert alert-danger">{{ Session::get('message_delete') }}</div>
@endif

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#waiting">Szablony</a></li>
</ul>

<div class="tab-content">
    <div id="waiting" class="tab-pane fade in active">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-striped thead-inverse">
                <thead class="black-head">
                    <tr>
                        <td class="xsm-col-th">Lp.</td>
                        <td class="sm-col-th">Data Utworzenia</td>
                        <td>Szablon stworzony przez </td>
                        <td>Nazwa szablonu</td>
                        <td>Nazwa testu</td>
                        <td class="md-col-th">Edycja</td>
                        <td class="md-col-th">Usuń</td>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($template as $item)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->cadre->first_name . ' ' . $item->cadre->last_name}}</td>
                            <td>{{$item->template_name}}</td>
                            <td>{{$item->name}}</td>
                            <td>
                                <a class="btn btn-default" href="{{ URL::to('/viewTestTemplate') }}/{{$item->id}}">
                                    <span style="color: green" class="glyphicon glyphicon glyphicon-pencil"></span> Edytuj
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-default delete_test" data-id ={{$item->id}} href="#">
                                    <span style="color: green" class="glyphicon glyphicon glyphicon-trash"></span> Usuń
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer">Brak testów w tej kategorii!</div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')
<script>

    // po kliknięciu usuń.
    $('.delete_test').on('click',function (e) {
         var id = $(this).data('id');
       //
        swal({
            title: 'Jesteś pewien?',
            text: "Cofnięcie zmian nie będzie możliwe.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tak, usuń test!'
        }).then((result) => {
            if (result.value) {
            swal(
                'Usunieto!',
                'Wybrany test został usuniety.',
                'success'
            )
            window.location.replace('{{ URL::to('/deleteTemplate') }}'+'/'+id);
        }
    })
    });


</script>
@endsection
