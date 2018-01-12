@extends('layouts.main')
@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
<style>
    .dropdown-menu{
        left: 0px;
    }
    .checked{
        background: red !important;;
    }
    .no_checked{
        background: #f9f9f9 !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Dodaj test</h1>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Dodaj test</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Temat: </div>
                                <input type="text" id="subject_input" class="form-control" name="subject" placeholder="podaj temat.." value="">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Test dla: </div>
                                    <select class="form-control" id="user_select">
                                        @foreach($users as $user)
                                            <option value={{$user->id}}>{{$user->last_name.' '.$user->first_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Zagadnienia: </div>
                                @foreach($categories as $category)
                                    <p><button id={{'categoryid_'.$category->id}} class="category" data-toggle="modal" data-target="#myModal" value={{$category->name}}>{{$category->name}}</button> <br></p>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Wybrane Pytania</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="all_question">
                            <thead>
                            <tr>
                                <td>Temat</td>
                                <td>Treść</td>
                                <td>Czas na pytanie</td>
                                <td>Akcja</td>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <button id="save_button">zapisz</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<!-- Sekcja z modalami -->


<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        Lista Pytań <div id="category" style="display: inline"></div><br>
                        Ilość wybranych pytań dla testu: <div id="count_question" style="display: inline"></div>
                    </h4>

                </div>

                <div class="modal-body">
                    <table id="question_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Treść</th>
                                <th>Czas</th>
                                <th>Akcja</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>




@endsection

@section('script')
<script>
    // Tablica do przechowywania informacji o wybranym pytaniu
    var question_text_array = [];
    // tablica id
    var question_array_id = [];
    // Ilość wybranych pytań
    var question_count = 0;
    // Nazwa Kategorii
    var category_name = "";
    // id kategorii
    var category_id  = 1;
    //Domyślna wartość czasu na pytanie pobrana z bazy
    var time_question_from_database = 5;
    //które pytania powtarzają się dla użytkownika
    var question_repeat = [1,5];
    // Tablica do przechowywania losowych indeksów
    var random_array = [];


 $(document).ready( function () {

    // funkcja do sprawdzania czy danyc element jest w tabeli pod indeksem id
    function checkElementInArray(array,element) {
        for(var i=0;i<array.length;i++)
        {
            if(array[i].id == element)
                return true;
        }
        return false;
    }


     table_question = $('#question_table').DataTable({
         "dom": '<"toolbar">Bfrti',
             "autoWidth": false,
             "processing": true,
             "serverSide": true,
             "paging": false,
             "language": {
                 "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
             },
             "drawCallback": function (settings) {
             },
             "ajax": {
                 'url': "{{ route('api.showQuestionDatatable') }}",
                 'type': 'POST',
                 'data': function (d) {
                     d.category_id = category_id;
                 },
                 'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
             },// przed renderem wiersza możliwość dodawania stylów
         "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
             if(checkElementInArray(question_text_array,aData.id))
                 $(nRow).addClass('checked');
             else
                $(nRow).addClass('no_checked');
             $(nRow).attr('id', aData.id);
             return nRow;
             // po wyświetleniu strony dodaj nagłówek z możliwościa losowania pytań
         },"fnDrawCallback": function(settings){
             // Dodanie nagłówka do datatable
             var api = new $.fn.dataTable.Api( settings );
             var html_toolbar = '<label>Losuj pytania: <select id=question_random_count> <option>Wybierz</option>';
             for(var i = 1; i<=api.rows().count();i++)
             {
                 if(i<5)
                     html_toolbar += '<option>'+i+'</option>';
                 else
                     break;
             }
             html_toolbar += '</select></label>';
             $('div.toolbar').html(html_toolbar);

             // Funkcja losująca
             $('#question_random_count').on('change',function (e) {
                 // Ilość pytań do wylosowania
                 var count_random_question = $(this).val();
                 // wyzerowanie tablicy losowych wierszy
                 random_array = [];
                 for(var i=0;i<question_text_array.length;i++){
                     // tylko z tej samej kategorii
                     if(question_text_array[i].subject == category_name)
                     { // znajdz po nr:id wiersz w tabeli z pytaniami, i kliknij w przycisk wybierz
                         var choisen_tr = $('#'+question_text_array[i].id).find('.button_question_choice');
                         $(choisen_tr).trigger('click');
                         i--;
                     }
                 }
                 if(count_random_question != 'Wybierz')
                 {  //zliczenie ilość pytań w kategorii
                     var table_question_row_count = table_question.rows().count();
                     //zmienna losowa
                     var random_value = 0;
                     //iteracja po ilości wymaganych pytań
                     for(var i=0;i<count_random_question;)
                     {  //losowanie liczby
                         random_value = Math.floor(Math.random() * table_question_row_count);
                         // jeśli nie ma jej w tablicy dodaj ? wylosuj jeszcze raz
                         if(jQuery.inArray(random_value,random_array) == -1){
                             random_array.push(random_value);
                             i++;
                         }
                     }
                     // po wybraniu losowych wierszy kliknij przycisk
                     for(var i=0;i<random_array.length;i++){
                         var choisen_row = table_question.row(random_array[i]).data().id;
                         var choisen_tr = $('#'+choisen_row).find('.button_question_choice');
                         $(choisen_tr).trigger('click');
                     }
                 }
             });

         }  , "columns": [

                 {"class" : "question_text","data": "content"},
                 { "width": "10%","data": function (data, type, dataToSet) {
                     return '<input type="number" class="form-control question_time" placeholder="min" value='+data.default_time+'>';
                    }
                 },
                 { "data": function (data, type, dataToSet) {
                     return '<button class="button_question_choice">Wybierz</button>';
                 }
             }
             ],
         });
 });

    // generowanie tabeli z zaznaczonymi pytaniami
     table_all_guestion= $('#all_question').DataTable({
         "dom": 'lBfrtip',
         "autoWidth": false,
         "language": {
             "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
         },
         "columns":[
             {"width": "10%"},
             null,
             {"width": "10%"},
             {"width": "10%"}
         ]
     });

     // zmiana nazwy kategoerii na modalu
     $('.category').on('click',function (e) {
         category_name = $(this).attr('value');
         category_id = $(this).attr('id');
         category_id = category_id.split("_");
         category_id = category_id[1];
         $('#category').text(category_name);
         table_question.ajax.reload();

     });


     // Funkcja do usuwania elementów z tablicy no indeksie
     function removeFunction (myObjects,prop,valu)
     {
         var what_delete = null;
            for(var i=0;i<myObjects.length;i++)
             {
                 if(myObjects[i][prop] == valu)
                 {
                     what_delete = i;
                     break;
                 }
             }
             if(what_delete != null)
                myObjects.splice(what_delete,1);
            return myObjects;
     }


     // zapisanie testu
    $('#save_button').on('click',function (e) {
        var id_user = $('#user_select').val();
        var subject = $('#subject_input').val();
        $.ajax({
            type:"POST",
            url: '{{ route('api.saveTestWithUser') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                "question_test_array":question_text_array,
                "id_user": id_user,
                "subject": subject
            },
            success: function(response) {
               console.log("Zapis do bazy");
            }
        });
    });

     $('#all_question').on('focusout','.question_time',function (e) {

         if($(this).val() > 10 )
         {
             alert('za dużo')
         }else if($(this).val() < 1)
         {
             alert('za mało')

         }else
         // wyłuskanie tr należącego do button
         var tr = $(this).closest('tr').attr('id');
         tr = tr.split("_");
         //przypisane question_id
         var question_id = tr[1];
         for(var i=0;i<question_text_array.length;i++)
         {
             if(question_text_array[i].id == question_id)
                 question_text_array[i].time = $(this).val();
         }
     });
     $('#all_question').on('click','.delete_row',function (e) {
         // wyłuskanie tr należącego do button
         var tr = $(this).closest('tr').attr('id');
         tr = tr.split("_");
         var question_id = tr[1];
         //usunięcie wiersza z tabeli z pytaniami
         table_all_guestion.row('#question_'+question_id).remove().draw();
         //usunicie informacji o wierszu z tabeli
         removeFunction(question_text_array,"id",question_id);
         // zmniejsz ilość wybranych pytań
         question_count--;
     });



        // ręczne wybieranie pytań
    $('#question_table tbody').on( 'click', 'button',function () {
         // Znajdź informacje o wierszu
         var tr = $(this).closest('tr');
         var tr_class = tr.attr('class');
         tr_class = tr_class.split(" ");
         var tr_class_name = tr_class[1];
        // cd.
         var question_text = tr.find('td.question_text').text();
         var question_id = tr.attr('id');
         var question_time = tr.find('td input').val();
         // gdy nie ma wybranego czasu na pytanie
         if(question_time =='')
         {
             question_time = time_question_from_database;
         }
        // gdy wiersz nie jest zaznaczony: Działaj
         if(tr_class_name == 'no_checked' )
         {
             // dodaj wiersz do datatable -> tabela pod modalem
            var rowNode =  table_all_guestion.row.add([
                category_name,
                question_text,
                '<input type="number" class="form-control question_time" placeholder="min" value='+question_time+'>',
                '<button type="button" class="btn btn-danger delete_row">Usuń</button>'
            ]).node();
             rowNode.id = "question_"+question_id;
             // wpisanie informacji o pytaniu do tablicy
             question_text_array.push({id:question_id,text:question_text,time:question_time,subject:category_name});
            //gdy pytanie jest powtórzone zaznacz na innny kolor
             if(jQuery.inArray(parseInt(question_id),question_repeat) != -1)
                 $(rowNode).css('background','black');
             // dodanie klasy z informacją że wiersz jest zaznaczony
             tr.removeClass(tr_class[0]+' no_checked').addClass(tr_class[0]).addClass('checked');
             //powiększ ilość pytań
             question_count++;
         }else {
             // usuń wiersz z tabeli pod modalem
             $('#question_'+question_id).remove();
             //to samo w datatable, chyba zbędne wyżej
             table_all_guestion.row('#question_'+question_id).remove().draw();
             //usunicie informacji o wierszu z tabeli
             removeFunction(question_text_array,"id",question_id);
             // zmiana flagi w klacie -> wyłączenie koloru
             tr.removeClass(tr_class[0]+' checked').addClass(tr_class[0]).addClass('no_checked');

             // zmniejsz ilość wybranych pytań
             question_count--;
         }
         //przechowywanie id pytania
        question_array_id = [];
         for(var i =0;i<question_text_array.length;i++)
         {
             question_array_id.push(question_text_array[i].id);
         }
         // renderuj tabele
         table_all_guestion.draw();
         // zmień wratość wybranych pytań na stronie;
         $('#count_question').text(question_count);
         //console.log(question_text_array);
 })
</script>
@endsection
