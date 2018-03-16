{{--@extends('layouts.tyleoile')--}}
{{--@section('content')--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Trebuchet MS", "Tahoma", sans-serif;
    }

    body {
        width: 100vw;
        height: 100vh;
    }

    .wraper {
        width: 100vw;
        height: 100vh;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    /* ***************HEADER************** */

    header {
        width: 100%;
        height: 20%;
        padding-bottom: 20px;
        background-color: #2A2A2A;

        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
    }

    header > div {
        color: #FFF6B1;
    }

    .first {
        height: 100%;

        display:flex;
        flex-grow: 1;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .second {
        height: 100%;

        display:flex;
        flex-grow: 1;
        flex-direction: row;
        align-items:flex-start;
        justify-content: flex-end;
        padding-top: 10px;
        margin-right: 55px;
        color: white;
    }

    #first-span {
        font-size: 3.7em;
    }

    #second-span {
        font-size: 2.4em;
    }

    .no-data {
        font-size: 5.2em;
        color: red;
        padding: 50px;
    }
    /* ************END HEADER*********** */

    section {
        width: 100%;
        height: 80%;

        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: flex-start;
    }

    /* ************TABLE**************** */

    table {
        font-size: 2.2em;
    }

    th:first-of-type {
        width: 7%;
    }

    tr {
        height: 8.8vh;
    }

    td {
        text-align: center;
    }

    th {
        text-align: center;
    }

    td:first-of-type {
        width: 7%;
    }

    thead {
        background: linear-gradient(dimgray, white);
    }

    tbody > tr:nth-of-type(2n) {
        background-color: #EDEDF4;
    }
</style>

<div class="wraper">
    <header>
        <div class="first"><span id="first-span">Bieżące wyniki <script>
                         /***********Displaying actual date*********/
                         setInterval(function() {
                             var today = new Date();
                             $('#first-span').text('Bieżące wyniki ');

                             /*****Adding 0 to numbers less than 10*****/
                             if(today.getMinutes() < 10) {
                                 var minuty = '0' + today.getMinutes();
                             } else {
                                 var minuty = today.getMinutes();
                             }

                             if(today.getHours() < 10) {
                                 var godzina = '0' + today.getHours();
                             } else {
                                 var godzina = today.getHours();
                             }

                             if(today.getMonth() < 10) {
                                 var miesiac = '0' + today.getMonth();
                             } else {
                                 var miesiac = today.getMonth();
                             }

                             if(today.getDate() < 10) {
                                 var dzien = '0' + today.getDate();
                             } else {
                                 var dzien = today.getDate();
                             }

                             if(today.getSeconds() < 10) {
                                 var sekundy = '0' + today.getSeconds();
                             } else {
                                 var sekundy = today.getSeconds();
                             }
                             /****END***/

                             var actualDate = dzien + '.' + miesiac + '.' + today.getFullYear() + 'r. ' + godzina + ':' + minuty + ':' + sekundy;
                             $('#first-span').text('Bieżące wyniki ');
                             $('#first-span').append(actualDate);
                         },1000);

                         /******Refreshing page every hour******/
                         var today = new Date();
                         setInterval(function(){
                             today = new Date();
                         }, 1000);
                         var actualHour = today.getHours();
                         setInterval(function(){
                             if(actualHour - today.getHours() != 0) {
                                 window.location.reload(1);
                             }
                         }, 1000);
                    </script></span></div>
        <div class="second">
            <div class="second-cont-inside">
                <span id="second-span">

                </span>
            </div>
        </div>
    </header>

    <section>
        @if(isset($dane))
        <table class="table">
            <thead>
            <tr>
                <th>L.P</th>
                <th>Imie &amp; Nazwisko</th>
                <th>Czas</th>
                <th>L.zgód</th>
                <th>Średnia</th>
                <th>Pln/h</th>
            </tr>
            </thead>

            <tbody class="table-body">
            @foreach($dane as $user)
            <tr class="one">
                <td class="indx"></td>
                <td>{{$user['username']}}</td>
                <td>{{$user['pole1']}}</td>
                <td>{{$user['pole2']}}</td>
                <td>{{$user['pole3']}}</td>
                <td>{{$user['pole4']}}</td>
            </tr>
            @endforeach

            </tbody>
        </table>
        @else
        <div class="no-data">Brak danych!</div>
            <script>
                setInterval(function(){
                        window.location.reload(1);
                }, 30000);
            </script>
        @endif
    </section>

<script>
    $(document).ready(function() {
        var tablica = [];
        /***********Filling L.P column*********/
        var indx = $('.indx');
        indx.each(function(index) {
            $(this).prepend(index+1);
        });
        /*************End Filling*************/

        var rekordy = $('tbody tr');
        rekordy.each(function() {
            var danyRekord = $(this);
            tablica.push(danyRekord);
        });

        /*****Function that splits data rows into array of objects*****/
        var chunks = function(array, size) {
            var results = [];
            while (array.length) {
                results.push(array.splice(0, size));
            }
            return results;
        };
        /***********End of function*********/

        var delayInMilliseconds = 8000;
        var newTable = chunks(tablica,8);
        var tableBody = $('.table-body');
        tableBody.text(' ');
        tableBody.append(newTable[0]);
        var iteracja = 0;

        setInterval(function() {
            tableBody.text(' ');
            tableBody.append(newTable[iteracja]);
            iteracja++;
            if(iteracja == newTable.length) {
                iteracja = 0;
            }
        },delayInMilliseconds);
    });
</script>
</div>
{{--@endsection--}}










