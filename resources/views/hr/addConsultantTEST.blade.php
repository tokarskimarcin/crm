@extends('layouts.main')
@section('content')


    <style>
        .edit{
            background-color: #FFFFFF;
            border:0;
        }
        .edit:hover{
            color:#a7a7a7;
        }
        .edit:active{
            border:0;
        }
        .edit-name{
            display:none;
        }
        .edit-surname{
            display:none;
        }
        .edit-doc{
            display:none;
        }
        .edit-stu{
            display:none;
        }
        .edit-statuswork{
            display:none;
        }
        .edit-phonework{
            display:none;
        }
        .edit-phonepriv{
            display:none;
        }
        .edit-datework{
            display:none;
        }
        .edit-money{
            display:none;
        }
        .edit-addmoney{
            display:none;
        }
    </style>

    <!-- Main -->
    <div class="container">
        <div class="row">
            <!-- center left-->
            <div class="col-md-12">
                <hr>
                <!-- <div class="alert alert-danger" role="alert">Niestety ta funkcja nie jest jeszcze dostępna.</div> -->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Profil Pracownika</h3>
                    </div>
                    <div class="panel-body">
                        <!-- <div class="col-md-2 col-lg-2 " align="center"> <img alt="User Pic" src="http://saintgeorgelaw.com/wp-content/uploads/2015/01/male-formal-business-hi.png" class="img-circle img-responsive" style="border:2px solid #222;"> </div> -->
                        <div class="col-md-10">

                            <div class=" col-md-6 col-lg-6 ">
                                <table class="table table-user-information">
                                    <tbody>
                                    <b style="font-size: 20px; font-family: sans-serif;">Dane Osobowe</b>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Imię:</b></td>
                                        <td>
                                            <input type="text" class="form-control" name="first_name" placeholder="Imię">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Nazwisko:</b></td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Nazwisko" name="last_name"  value="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Telefon:</b></td>
                                        <td>
                                            <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" name="phone" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Login(Godzinówka):</b></td>
                                        <td><input type="text" class="form-control" placeholder="Login" name="username" value=""></td>

                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Hasło:</b></td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Hasło" name="password"  value="">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Dokumenty:</b></td>
                                        <td>
                                            <select class="form-control" style="font-size:18px;" name="documents" >
                                                <option>Wybierz</option>
                                                <option value="1">Tak</option>
                                                <option value="0">Nie</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Student:</b></td>
                                        <td>
                                            <select class="form-control" style="font-size:18px;" name="student">
                                                <option>Wybierz</option>
                                                <option value="1">Tak</option>
                                                <option value="0">Nie</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Agencja:</b></td>
                                        <td>
                                            <select class="form-control" style="font-size:18px;" name="agency_id" >
                                                <option>Wybierz</option>
                                                @foreach($agencies as $agency)
                                                    <option value="{{$agency->id}}">{{$agency->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class=" col-md-6 col-lg-6 ">
                                <table class="table table-user-information">
                                    <tbody>
                                    <b style="font-size: 20px; font-family: sans-serif;">Informacje cd</b>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Rozpoczęcie Pracy:</b></td>
                                        <td>
                                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                <input class="form-control" name="start_date" type="text" value="{{date("Y-m-d")}}" readonly >
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Stawka na godzine:</b></td>
                                        <td>
                                            <select class="form-control" style="font-size:18px;" name="rate" >
                                                <option>Wybierz</option>
                                                <option>Nie dotyczy</option>
                                                @for ($i = 7.00; $i <=14; $i+=0.5)
                                                    <option value="{{number_format ($i,2)}}">{{number_format ($i,2)}}</option>
                                                @endfor
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Całość na konto:</b></td>
                                        <td>
                                            <select class="form-control" style="font-size:18px;" name="salary_to_account">
                                                <option>Wybierz</option>
                                                <option value="1">Tak</option>
                                                <option value="0">Nie</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 170px;height:52px;"><b>Login PBX:</b></td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Login z programu do dzwonienia" name="login_phone" value="">
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
            </div>
        </div>
    </div>
    <!--/container-->
    <!-- /Main -->
    <div class="modal">

    </div>

@endsection
@section('script')

<script>

    $("#edit-name-button").click(function(){
        $(".name").fadeOut();
        $(".edit-name").delay(500).fadeIn();

    });
    $("#edit-surname-button").click(function(){
        $(".surname").fadeOut();
        $(".edit-surname").delay(500).fadeIn();

    });
    $("#edit-doc-button").click(function(){
        $(".doc").fadeOut();
        $(".edit-doc").delay(500).fadeIn();

    });
    $("#edit-stu-button").click(function(){
        $(".stu").fadeOut();
        $(".edit-stu").delay(500).fadeIn();

    });
    $("#edit-statuswork-button").click(function(){
        $(".statuswork").fadeOut();
        $(".edit-statuswork").delay(500).fadeIn();

    });
    $("#edit-phonework-button").click(function(){
        $(".phonework").fadeOut();
        $(".edit-phonework").delay(500).fadeIn();

    });
    $("#edit-phonepriv-button").click(function(){
        $(".phonepriv").fadeOut();
        $(".edit-phonepriv").delay(500).fadeIn();

    });
    $("#edit-datework-button").click(function(){
        $(".datework").fadeOut();
        $(".edit-datework").delay(500).fadeIn();

    });
    $("#edit-money-button").click(function(){
        $(".money").fadeOut();
        $(".edit-money").delay(500).fadeIn();

    });
    $("#edit-addmoney-button").click(function(){
        $(".addmoney").fadeOut();
        $(".edit-addmoney").delay(500).fadeIn();

    });
    $("#edit-branch-button").click(function(){
        $(".branch").fadeOut();
        $(".edit-branch").delay(500).fadeIn();

    });
    $("#edit-priv-button").click(function(){
        $(".priv").fadeOut();
        $(".edit-priv").delay(500).fadeIn();

    });
    $("#edit-agency-button").click(function(){
        $(".agency").fadeOut();
        $(".edit-agency").delay(500).fadeIn();

    });

</script>
@endsection
