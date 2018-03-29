@extends('layouts.main')
@section('content')
    <style>
        th:nth-of-type(1) {
            width: 25%;
        }
        th:nth-of-type(2) {
            width: 10%;
        }
        th:nth-of-type(3) {
            width: 10%;
        }
        th:nth-of-type(4) {
            width: 50%;
        }

        th:nth-of-type(5) {
            width: 5%;
        }

        .panel-default > .panel-heading {
            background: #83BFC6;
        }

        .gray-nav {
            background: #02779E;
        }


        .inactivePanel {
            display: none;
        }

        .activePanel {
            display: block;
        }

    </style>

    <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Audyt</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default panel-primary first-panel">
            <div class="panel-heading">
                <p>Nazwa Panelu</p>
            </div>
            <div class="panel-body">
                <form method="post" action="" id="formulaz">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row first-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Oddział:</label>
                                <select class="form-control" style="font-size:18px;" id="department_info" name="department_info">
                                    <option value="0">Wybierz</option>
                                    @if(isset($dept))
                                        @foreach($dept as $d)
                                            <option value="{{$d->id}}">{{$d->departments->name}} {{$d->department_type->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="well well-lg">
                                <p style="text-align:center;">Krok 1: Wybierz departament.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row second-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Trener:</label>
                                <select class="form-control" style="font-size:18px;" id="trainer" name="trainer">
                                    <option value="0" id="trainerDefaultValue">Wybierz</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="well well-lg">
                                <p style="text-align:center;">Krok 2: Wybierz trenera z listy.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row third-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Data:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control" name="date" id="date" type="text" value="{{date("Y-m-d")}}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="well well-lg">
                                <p style="text-align:center;">Krok 3: Wybierz date audytu a następnie kliknij przycisk.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row fourth-row">
                        <div class="col-md-12">
                            <input class="btn btn-info btn-block" type="button" id="firstButton" value="Generuj raport">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
            <div class="panel panel-default second-panel">
                <div class="panel-heading titleOfSecondPanel">
                    <p>Nazwa drugiego panelu</p>
                </div>
                <div class="panel-body">
                    <form action="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="first">Kryteria</th>
                                        <th>Ilość</th>
                                        <th>Jakość</th>
                                        <th>Komentarz</th>
                                        <th>Zdjęcia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <div class="well well-sm"><p style="text-align:center;">Odprawa</p></div>
                                    <tr>
                                        <td class="first">Podanie wyników</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaScoresAmmout" name="odprawaScoresAmmout">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaScoresQuality" name="odprawaScoresQuality">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" id="odprawaScoresComment" name="odprawaScoresComment" class="form-control" style="width:100%;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input name="odprawaScoresFiles[]" id="odprawaScoresFiles" type="file" multiple="" />
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="first">Bieżąca sytuacja zespołu</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaSituationAmmout" name="odprawaSituationAmmout">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaSituationQuality" name="odprawaSituationQuality">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="odprawaSituationComment" name="odprawaSituationComment" style="width:100%;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input name="odprawaSituationFiles[]" id="odprawaSituationFiles" type="file" multiple="" />
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="first">Odprawa w swoich grupach</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaGroupsAmmout" name="odprawaGroupsAmmout">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaGroupsQuality" name="odprawaGroupsQuality">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="odprawaGroupsComment" name="odprawaGroupsComment" style="width:100%;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input name="odprawaGroupsFiles[]" id="odprawaGroupsFiles" type="file" multiple="" />
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="first">Motywowanie</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaMotivationAmmout" name="odprawaMotivationAmmout">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaMotivationQuality" name="odprawaMotivationQuality">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" style="width:100%;" id="odprawaMotivationComment" name="odprawaMotivationComment">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input name="odprawaMotivationFiles[]" id="odprawaMotivationFiles" type="file" multiple="" />
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="first">Przejrzystość informacji</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaInformationAmmout" name="odprawaInformationAmmout">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" style="font-size:18px;" id="odprawaInformationQuality" name="odprawaInformationQuality">
                                                    <option value="0">--</option>
                                                    <option value="1">Tak</option>
                                                    <option value="2">Nie</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="odprawaInformationComment" name="odprawaInformationComment" style="width:100%;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input name="odprawaInformationFiles[]" id="odprawaInformationFiles" type="file" multiple="" />
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>


                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="first">Kryteria</th>
                                    <th>Ilość</th>
                                    <th>Jakość</th>
                                    <th>Komentarz</th>
                                    <th>Zdjęcia</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div class="well well-sm"><p style="text-align:center;">Tablica</p></div>
                                <tr>
                                    <td class="first">Czy wyniki są aktualne?</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="criteriaActualScoresAmmout" name="criteriaActualScoresAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="criteriaActualScoresQuality" name="criteriaActualScoresQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="criteriaActualScoresComment" name="criteriaActualScoresComment" style="width:100%;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="criteriaActualScoresFiles[]" id="criteriaActualScoresFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Wytyczne przygotowania tablic</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="criteriaPreparationAmmout" name="criteriaPreparationAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="criteriaPreparationQuality" name="criteriaPreparationQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="criteriaPreparationComment" name="criteriaPreparationComment" style="width:100%;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="criteriaPreparationFiles[]" id="criteriaPreparationFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Czytelność</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="criteriaClearityAmmout" name="criteriaClearityAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="criteriaClearityQuality" name="criteriaClearityQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="criteriaClearityComment" name="criteriaClearityComment" style="width:100%;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="criteriaClearityFiles[]" id="criteriaClearityFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Estetyka tablicy</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="criteriaAestheticAmmout" name="criteriaAestheticAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="criteriaAestheticQuality" name="criteriaAestheticQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id='criteriaAestheticComment' name="criteriaAestheticComment" style="width:100%;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="criteriaAestheticFiles[]" id="criteriaAestheticFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="first">Kryteria</th>
                            <th>Ilość</th>
                            <th>Jakość</th>
                            <th>Komentarz</th>
                            <th>Zdjęcia</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div class="well well-sm"><p style="text-align:center;">Organizacja czasu pracy</p></div>
                        <tr>
                            <td class="first">Planowanie pracy w kalendarzu</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timePlaningAmmout" name="timePlaningAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timePlaningQuality" name="timePlaningQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="timePlaningComment" name="timePlaningComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="timePlaningFiles[]" id="timePlaningFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="first">Aktualny plan dnia</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timeDayPlanAmmout" name="timeDayPlanAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timeDayPlanQuality" name="timeDayPlanQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="timeDayPlanComment" name="timeDayPlanComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="timeDayPlanFiles[]" id="timeDayPlanFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="first">Rozliczenie coachingów</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timeCoachingAmmout" name="timeCoachingAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timeCoachingQuality" name="timeCoachingQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="timeCoachingComment" name="timeCoachingComment" style="width:100%;">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="timeCoachingFiles[]" id="timeCoachingFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="first">Trener pracuje zgodnie z planem</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timeTrainerAmmout" name="timeTrainerAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timeTrainerQuality" name='timeTrainerQuality'>
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="timeTrainerComment" name="timeTrainerComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="timeTrainerFiles[]" id="timeTrainerFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="first">Trener potrafi logicznie wyjaśnić plan działań</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timeLogicAmmout" name="timeLogicAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="timeLogicQuality" name="timeLogicQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="timeLogicComment" name="timeLogicComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="timeLogicFiles[]" id="timeLogicFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="first">Kryteria</th>
                            <th>Ilość</th>
                            <th>Jakość</th>
                            <th>Komentarz</th>
                            <th>Zdjęcia</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div class="well well-sm"><p style="text-align:center;">Tabela postępów</p></div>
                        <tr>
                            <td class="first">Dane wpisywane są aktualnie</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tableActualDataAmmout" name="tableActualDataAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tableActualDataQuality" name="tableActualDataQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="tableActualDataComment" name="tableActualDataComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="tableActualDataFiles[]" id="tableActualDataFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="first">Liczba zdarzeń realizowana</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tableRealizedAmmout" name="tableRealizedAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tableRealizedQuality" name="tableRealizedQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="tableRealizedComment" name="tableRealizedComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="tableRealizedFiles[]" id="tableRealizedFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="first">Wybór osób do pracy</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tablePeopleAmmout" name="tablePeopleAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tablePeopleQuality" name="tablePeopleQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="tablePeopleComment" name="tablePeopleComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="tablePeopleFiles[]" id="tablePeopleFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="first">Coachingi są rozliczone</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tableCoachingsAmmout" name="tableCoachingsAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tableCoachingsQuality" name="tableCoachingsQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="tableCoachingsComment" name="tableCoachingsComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="tableCoachingsFiles[]" id="tableCoachingsFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="first">Czas na rozliczenie coachingów</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tableTimeCoachingsAmmout" name="tableTimeCoachingsAmmout">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" style="font-size:18px;" id="tableTimeCoachingsQuality" name="tableTimeCoachingsQuality">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="width:100%;" id="tableTimeCoachingsComment" name="tableTimeCoachingsComment">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="tableTimeCoachingsFiles[]" id="tableTimeCoachingsFiles" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="first">Kryteria</th>
                                    <th>Ilość</th>
                                    <th>Jakość</th>
                                    <th>Komentarz</th>
                                    <th>Zdjęcia</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div class="well well-sm"><p style="text-align:center;">Coachingi</p></div>
                                <tr>
                                    <td class="first">Ilość coachingów</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="coachingsAmmout" name="coachingsAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="coachingsQuality" name="coachingsQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="coachingsComment" name="coachingsComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="coachingsFiles[]" id="coachingsFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Karty coachingowe</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="coachingsCartsAmmout" name="coachingsCartsAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="coachingsCartsQuality" name="coachingsCartsQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="coachingsCartsComment" name="coachingsCartsComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="coachingsCartsFiles[]" id="coachingsCartsFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Metoda CLEAR</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="coachingsClearAmmout" name="coachingsClearAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="coachingsClearQuality" name="coachingsClearQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="coachingsClearComment" name="coachingsClearComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="coachingsClearFiles[]" id="coachingsClearFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Ciągłość pracy</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="coachingsWorkAmmout" name="coachingsWorkAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="coachingsWorkQuality" name="coachingsWorkQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="coachingsWorkComment" name="coachingsWorkComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="coachingsWorkFiles[]" id="coachingsWorkFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="first">Kryteria</th>
                                    <th>Ilość</th>
                                    <th>Jakość</th>
                                    <th>Komentarz</th>
                                    <th>Zdjęcia</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div class="well well-sm"><p style="text-align:center;">Informacja zwrotna</p></div>
                                <tr>
                                    <td class="first">Ilość informacji zwrotnych</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="feedbackInfoAmmout" name="feedbackInfoAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="feedbackInfoQuality" name="feedbackInfoQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="feedbackInfoComment" name="feedbackInfoComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="feedbackInfoFiles[]" id="feedbackInfoFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Stosowanie SPINKI</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="feedbackSpinkiAmmout" name="feedbackSpinkiAmmout">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="feedbackSpinkiQuality" name="feedbackSpinkiQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="feedbackSpinkiComment" name="feedbackSpinkiComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="feedbackSpinkiFiles[]" id="feedbackSpinkiFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Wybór osób do pracy</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="feedbackWorkforceAmount" name="feedbackWorkforceAmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="feedbackWorkforceQuality" name="feedbackWorkforceQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="feedbackWorkforceComment" name="feedbackWorkforceComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="feedbackWorkforceFiles[]" id="feedbackWorkforceFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="first">Kryteria</th>
                                    <th>Ilość</th>
                                    <th>Jakość</th>
                                    <th>Komentarz</th>
                                    <th>Zdjęcia</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div class="well well-sm"><p style="text-align:center;">Sukcesorzy</p></div>
                                <tr>
                                    <td class="first">Ilość sukcesorów</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="SuccessorsAmount" name="SuccessorsAmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="SuccessorsQuality" name="SuccessorsQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="SuccessorsComment" name="SuccessorsComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="SuccessorsFiles[]" id="SuccessorsFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Praca nad sukcesorem</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="SuccessorsWorkwithAmount" name="SuccessorsWorkwithAmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="SuccessorsWorkwithQuality" name="SuccessorsWorkwithQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="SuccessorsWorkwithComment" name="SuccessorsWorkwithComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="SuccessorsWorkwithFiles[]" id="SuccessorsWorkwithFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>


                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="first">Kryteria</th>
                                    <th>Ilość</th>
                                    <th>Jakość</th>
                                    <th>Komentarz</th>
                                    <th>Zdjęcia</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div class="well well-sm"><p style="text-align:center;">Konkursy i system motywacyjny</p></div>
                                <tr>
                                    <td class="first">Zna system motywacyjny</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="contestKnowAmmount" name="contestKnowAmmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="contestKnowQuality" name="contestKnowQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="contestKnowComment" name="contestKnowComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="contestKnowFiles[]" id="contestKnowFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Zna aktualne konkursy</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="contestActualAmount" name="contestActualAmoun">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="contestActualQuality" name="contestActualQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="contestActualComment" name="contestActualComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="contestActualFiles[]" id="contestActualFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Potrafi policzyć swoje wynagrodzenie</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="contestCalculateAmount" name="contestCalculateAmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="contestCalculateQuality" name="contestCalculateQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="contestCalculateComment" name="contestCalculateComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="contestCalculateFiles[]" id="contestCalculateFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Wie co zrobić żeby wygrać konkurs</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="contestWinAmount" name="contestWinAmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="contestWinQuality" name="contestWinQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="contestWinComment" name="contestWinComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="contestWinFiles[]" id="contestWinFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="first">Kryteria</th>
                                    <th>Ilość</th>
                                    <th>Jakość</th>
                                    <th>Komentarz</th>
                                    <th>Zdjęcia</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div class="well well-sm"><p style="text-align:center;">Oddział</p></div>
                                <tr>
                                    <td class="first">Czystość w oddziale</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="departmentCleanAmount" name="departmentCleanAmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="departmentCleanQuality" name="departmentCleanQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="departmentCleanComment" name="departmentCleanComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="departmentCleanFiles[]" id="departmentCleanFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Podział na grupy</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="departmentGroupsAmount" name="departmentGroupsAmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="departmentGroupsQuality" name="departmentGroupsQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="departmentGroupsComment" name="departmentGroupsComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="departmentGroupsFiles[]" id="departmentGroupsFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="first">Reakcja na wyniki</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="departmentReactionAmount" name="departmentReactionAmount">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" style="font-size:18px;" id="departmentReactionQuality" name="departmentReactionQuality">
                                                <option value="0">--</option>
                                                <option value="1">Tak</option>
                                                <option value="2">Nie</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:100%;" id="departmentReactionComment" name="departmentReactionComment">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input name="departmentReactionFiles[]" id="departmentReactionFiles" type="file" multiple="" />
                                        </div>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <div class="row last-row">
                                <div class="col-md-12">
                                    <input class="btn btn-success btn-block" type="submit" id="secondButton" value="Zapisz audyt!" style="margin-bottom:1em;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script>
        $('.form_datetime').datetimepicker({
            //language:  'fr',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });
        $('.form_date').datetimepicker({
            language:  'fr',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
        $('.form_time').datetimepicker({
            language:  'fr',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0
        });

        $(document).ready(function() {
            /*************Functions related to event listeners*****************/

            /**
             * Function Show/Hide (2nd and next) steps and get list of trainers
             */
            function handleChange1() {
                if(inputDepartment.value != '0') { //activate 2nd step
                    secondStep.classList.remove('inactivePanel');
                    $('.generatedValues').remove(); //clear option list
                    $.ajax({ //generate list of trainers from given location
                        type: "POST",
                        url: '{{ route('api.ajax') }}',
                        data: {
                            "wybranaOpcja": inputDepartment.value
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            for(var i = 0; i < response.length; i++) {
                                var newItem = $('<option class="generatedValues" value="' + response[i].id + '">' + response[i].first_name + ' ' + response[i].last_name + '</option>');
                                $('#trainerDefaultValue').after(newItem);
                            }
                        }
                    });
                    return true;
                }
                else { //hide all previous divs and set value of 2nd div to default
                    secondStep.classList.add('inactivePanel');
                    $('#trainer').val('0'); //set value of trainer input back to 0
                    thirdStep.classList.add('inactivePanel');
                    fourthStep.classList.add('inactivePanel');
                    return true;
                }
            }

            /**
             *Function Show/Hide 3rd and 4th step.
             */
            function handleChange2() {
                if(inputDepartment.value != '0') {
                    thirdStep.classList.remove('inactivePanel');
                    fourthStep.classList.remove('inactivePanel');
                    return true;
                }
                else {
                    thirdStep.classList.add('inactivePanel');
                    fourthStep.classList.add('inactivePanel');
                    return true;
                }
            }

            /**
             *Function Hide first panel, show 2nd panel and sets heading for 2nd panel
             */
            var title = document.querySelector('.titleOfSecondPanel > p').firstChild;
            function handleFirstButtonClick() {
                secondPanel.classList.remove('inactivePanel');
                title.textContent = 'Audyt dla departamentu: ' + inputDepartment.options[inputDepartment.selectedIndex].text + ' wypełniony przez ' + inputTrainer.options[inputTrainer.selectedIndex].text + ' ' + inputDate.value;
                firstPanel.classList.add('inactivePanel');
            }
            /************ End of event listeners functions ************/


            //select every div that should disappear/appear at some point of user experience
           var firstPanel = document.getElementsByClassName('first-panel')[0];
           var secondPanel = document.getElementsByClassName('second-panel')[0];
           var secondStep = document.getElementsByClassName('second-row')[0];
           var thirdStep = document.getElementsByClassName('third-row')[0];
           var fourthStep = document.getElementsByClassName('fourth-row')[0];

           //Hiding divs at beggining
           secondPanel.classList.add('inactivePanel');
           secondStep.classList.add('inactivePanel');
           thirdStep.classList.add('inactivePanel');
           fourthStep.classList.add('inactivePanel');

            //Select inputs of first panel
           var inputDepartment = document.getElementById('department_info');
           var inputTrainer = document.getElementById('trainer');
           var inputDate = document.getElementById('date');
           var firstButton = document.getElementById('firstButton');

            //event listeners responsible for showing/hiding first panel divs
            inputDepartment.addEventListener('change', handleChange1);
            inputTrainer.addEventListener('change', handleChange2);
            firstButton.addEventListener('click', handleFirstButtonClick);

        });
    </script>


@endsection
