@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Profil kandydata</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Profil kandydata
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-size: 150px; color: #aaa" class="glyphicon glyphicon-user"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <b class="myLabel">Konrad Jarzyna</b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <b class="myLabel">Status rekrutacji:</b>
                                    <p class="myLabel">Nie odbiera</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Imie:</label>
                                        <input type="text" class="form-control" placeholder="Imie"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Nazwisko:</label>
                                        <input type="text" class="form-control" placeholder="Nazwisko"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Numer telefonu:</label>
                                        <input type="text" class="form-control" placeholder="000999888"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Oddział:</label>
                                        <select class="form-control">
                                            <option>Wybierz</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Źródło:</label>
                                        <select class="form-control">
                                            <option>Wybierz</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel">Status rekrutacji:</label>
                                        <select class="form-control">
                                            <option>Wybierz</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel"></label>
                                        <button class="btn btn-info" style="width: 100%">  
                                            <span class="glyphicon glyphicon-envelope"></span> Zapisz zmiany
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Opis:</label>
                                    <textarea rows="5" style="height: 100%" class="form-control" placeholder="Opis pracownika"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Rektutacja nr 1
            </div>
            <div class="panel-body">
      
                <ul class="nav nav-tabs" style="margin-bottom: 25px">
                    <li class="active"><a data-toggle="tab" href="#home">Etap 1</a></li>
                    <li><a data-toggle="tab" href="#menu1">Etap 2</a></li>
                    <li><a data-toggle="tab" href="#menu2">Etap 3</a></li>
                </ul>
                
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Dodanie kandydata
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Dane rekrutera:</label>
                                            <input type="text" class="form-control" value="Konrad Jarzyna"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Data:</label>
                                            <input type="text" class="form-control" value="2018-12-12 12:12:12"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Etap rekrutacji:</label>
                                            <input type="text" class="form-control" value="Dodanie kandydata"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-success" style="width: 100%">  
                                                    <span class="glyphicon glyphicon-ok"></span> Następny etap
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-danger" style="width: 100%">  
                                                    <span class="glyphicon glyphicon-remove"></span> Zakończ rekrutację
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Komentarz:</label>
                                            <textarea class="form-control" placeholder="Komentarz"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Rozmowa telefoniczna (odebrał)
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Dane rekrutera:</label>
                                            <input type="text" class="form-control" value="Konrad Jarzyna"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Data:</label>
                                            <input type="text" class="form-control" value="2018-12-12 12:12:12"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Etap rekrutacji:</label>
                                            <input type="text" class="form-control" value="Dodanie kandydata"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-success" style="width: 100%">  
                                                    <span class="glyphicon glyphicon-ok"></span> Następny etap
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-danger" style="width: 100%">  
                                                    <span class="glyphicon glyphicon-remove"></span> Zakończ rekrutację
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Komentarz:</label>
                                            <textarea class="form-control" placeholder="Komentarz"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Umówiony na rozmowę
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Dane rekrutera:</label>
                                            <input type="text" class="form-control" value="Konrad Jarzyna"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Data:</label>
                                            <input type="text" class="form-control" value="2018-12-12 12:12:12"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Etap rekrutacji:</label>
                                            <input type="text" class="form-control" value="Dodanie kandydata"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-success" style="width: 100%">  
                                                    <span class="glyphicon glyphicon-ok"></span> Następny etap
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-danger" style="width: 100%">  
                                                    <span class="glyphicon glyphicon-remove"></span> Zakończ rekrutację
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Komentarz:</label>
                                            <textarea class="form-control" placeholder="Komentarz"></textarea>
                                        </div>
                                    </div>
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
<script>

</script>
@endsection
