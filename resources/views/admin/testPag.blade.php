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
        /* font-family: "Helvetica", "Arial", sans-serif; */
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
        padding-right: 25px;
    }

    #first-span {
        font-size: 3em;
    }

    #second-span {
        font-size: 1.7em;
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
        font-size: 1.5em;
    }

    th:first-of-type {
        width: 7%;
    }

    td:first-of-type {
        width: 7%;
    }

    thead {
        background-color: #D0E0EB;
    }

    tbody > tr:nth-of-type(2n) {
        background-color: #EDEDF4;
    }

    /* .two {
      visibility: hidden;
    } */


</style>


<div class="wraper">
    <header>
        <div class="first"><span id="first-span">Bieżące wyniki:</span></div>
        <div class="second">
            <div class="second-cont-inside"><span id="second-span">18 lipiec 1992r</span>.</div>
        </div>
    </header>

    <section>
        <table class="table">
            <thead>
            <tr>
                <th>L.P</th>
                <th>Imie i nazwisko</th>
                <th>Czas</th>
                <th>Zgody</th>
                <th>Średnia</th>
                <th>PLN/H</th>
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
    </section>

<script>
    $(document).ready(function() {

        //Filling L.P column
        var indx = $('.indx');
        indx.each(function(index) {
            $(this).prepend(index+1);
        });
        var delayInMilliseconds = 5000;
        var numberOfElements = $('.indx:last').text();
        var intNumberOfElements = numberOfElements * 1

        var numberOfSlides = Math.ceil(intNumberOfElements / 8);

        var liczba
        var liczba2

        for(var j = 0; j < 20; j++) {


            for(var i = 0; i < numberOfSlides; i++) {
                if(i == 0) {
                    $('tbody tr:gt(7)').fadeOut(0);
                }
                else {
                    if((i*8 + 8) > numberOfElements) {
                        liczba = i * 8;
                        $('tbody tr').delay(10000).fadeOut(0);
                        $('tbody tr:gt(' + liczba + ')').fadeIn(0);
                    }
                    else {
                        liczba = i * 8;
                        liczba2 = i*8 + 8;
                        $('tbody tr').delay(10000).fadeOut(0);
                        $('tbody tr:gt(' + liczba + ')').fadeIn(0);
                        $('tbody tr:gt(' + liczba2 + ')').fadeOut(0);
                    }
                }
            }
            $('tbody tr:lt(' + liczba +')').fadeIn(0);
        }

        // $('.table').on('click', function(e) {



            // if(start == 0) {
                // $('tr[class="one"]').css('visibility', 'visible');
                // $('tr[class="two"]').css('visibility', 'collapse');
                // $('.one').delay(500).fadeIn(4000);
            //     $('.one').hide();
            //     $('.two').each(function(index) {
            //         $(this).fadeIn(700 * index);
            //     });
            //     console.log('pierwsze');
            //     start++;
            // }
            // else {
                // $('tr[class="one"]').css('visibility', 'collapse');
                // $('tr[class="two"]').css('visibility', 'visible');
                // $('.two').hide();
                // $('.two').each(function(index) {
                //     $(this).fadeIn(700 * index);
                // });
                // $('.two').delay(500).fadeIn(4000);
        //         console.log('drugie');
        //         start = 0;
        //     }
        // });

    });


</script>
</div>
{{--@endsection--}}










