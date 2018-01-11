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
        background: white !important;
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

                        <div class="col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">Temat: </div>
                                <input type="text" class="form-control" name="subject" placeholder="podaj temat.." value="">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">Zagadnienia: </div>
                                <p><button class="category" data-toggle="modal" data-target="#myModal" value="Fuko">FUKO</button> <br></p>
                                <p><button class="category" data-toggle="modal" data-target="#myModal" value="COACHING">COACHING</button> <br></p>
                                <p><button class="category" data-toggle="modal" data-target="#myModal" value="KOORDYNOWANIE">KOORDYNOWANIE</button> <br></p>
                            </div>
                        </div>


                        <div class="col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">Test dla: </div>
                                    <select class="form-control">
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski2</option>
                                        <option>Janusz Kowalski3</option>
                                        <option>Janusz Kowalski4</option>
                                        <option>Janusz Kowalski5</option>
                                        <option>Janusz Kowalski6</option>
                                        <option>Janusz Kowalski7</option>
                                        <option>Janusz Kowalski8</option>
                                        <option>Janusz Kowalski9</option>
                                    </select>
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

                        <tr id="1" class="no_checked">
                            <td class="question_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut corporis, distinctio dolore doloremque, eveniet expedita harum inventore maiores officia omnis quasi, reprehenderit similique tempore. Esse et inventore ratione recusandae similique.</td>
                            <td><input type="text" class="form-control question_time" placeholder="min" value=""></td>
                            <td><button class="button_question_choice">Wybierz</button></td>
                        </tr>

                        <tr id="2" class="no_checked">
                            <td class="question_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut corporis, distinctio dolore doloremque, eveniet expedita harum inventore maiores officia omnis quasi, reprehenderit similique tempore. Esse et inventore ratione recusandae similique.</td>
                            <td><input type="text" class="form-control question_time" placeholder="min" value=""></td>
                            <td><button class="button_question_choice">Wybierz</button></td>
                        </tr>

                        <tr id="3" class="no_checked">
                            <td class="question_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut corporis, distinctio dolore doloremque, eveniet expedita harum inventore maiores officia omnis quasi, reprehenderit similique tempore. Esse et inventore ratione recusandae similique.</td>
                            <td><input type="text" class="form-control question_time" placeholder="min" value=""></td>
                            <td><button class="button_question_choice">Wybierz</button></td>
                        </tr>

                        <tr id="4" class="no_checked">
                            <td class="question_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut corporis, distinctio dolore doloremque, eveniet expedita harum inventore maiores officia omnis quasi, reprehenderit similique tempore. Esse et inventore ratione recusandae similique.</td>
                            <td><input type="text" class="form-control question_time" placeholder="min" value=""></td>
                            <td><button class="button_question_choice">Wybierz</button></td>
                        </tr>

                        <tr id="5" class="no_checked">
                            <td class="question_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut corporis, distinctio dolore doloremque, eveniet expedita harum inventore maiores officia omnis quasi, reprehenderit similique tempore. Esse et inventore ratione recusandae similique.</td>
                            <td><input type="text" class="form-control question_time" placeholder="min" value=""></td>
                            <td><button class="button_question_choice">Wybierz</button></td>
                        </tr>

                        <tr id="6" class="no_checked">
                            <td class="question_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut corporis, distinctio dolore doloremque, eveniet expedita harum inventore maiores officia omnis quasi, reprehenderit similique tempore. Esse et inventore ratione recusandae similique.</td>
                            <td><input type="text" class="form-control question_time" placeholder="min" value=""></td>
                            <td><button class="button_question_choice">Wybierz</button></td>
                        </tr>


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
    // Ilość wybranych pytań
    var question_count = 0;
    // Nazwa Kategorii
    var category_name = "";
    //Domyślna wartość czasu na pytanie pobrana z bazy
    var time_question_from_database = 5;
    //które pytania powtarzają się dla użytkownika
    var question_repeat = [1,5];
    // Tablica do przechowywania losowych indeksów
    var random_array = [];
 $(document).ready( function () {

     table_question = $('#question_table').DataTable({
         "dom": '<"toolbar">lBfrtip'
     });
     table_all_guestion= $('#all_question').DataTable({
         "dom": 'lBfrtip'
     });
     // Dodanie nagłówka do datatable
     $('div.toolbar').html('' +
         '<label>Losuj pytania: ' +
         '<select id=question_random_count>' +
         '<option>Wybierz</option>' +
         '<option>1</option>' +
         '<option>2</option>' +
         '<option>3</option>' +
         '<option>4</option>' +
         '</select>' +
         '</label>');
     // zmiana nazwy kategoerii na modalu
     $('.category').on('click',function (e) {
         category_name = $(this).attr('value');
         $('#category').text(category_name);
     });

     $('myModal2').on('show.bs.modal',function (e) {


     });
     // Funkcja losująca
     $('#question_random_count').on('change',function (e) {
        // Ilość pytań do wylosowania
         var count_random_question = $(this).val();

         // usuniecie wszystkich zaznczonych pozycji przy pomocy random_array
         for(var i=0;i<random_array.length;i++){
             var choisen_row = table_question.row(random_array[i]).id();
             var choisen_tr = $('#'+choisen_row).find('.button_question_choice');
             $(choisen_tr).trigger('click');
         }
         // wyzerowanie tablicy losowych wierszy
         random_array = [];

         if(count_random_question != 'Wybierz')
         {  //zliczenie ilość pytań w kategorii
             var table_question_row_count = table_question.rows().count();
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

             // po byraniu losowych wierszy kliknij przycisk
             for(var i=0;i<random_array.length;i++){
                 var choisen_row = table_question.row(random_array[i]).id();
                 var choisen_tr = $('#'+choisen_row).find('.button_question_choice');
                 $(choisen_tr).trigger('click');
             }
         }
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

        // ręczne wybieranie pytań
     $(".button_question_choice").on('click',function () {
         // Znajdź informacje o wierszu
         var tr = $(this).closest('tr');
         var tr_class = tr.attr('class');
         tr_class = tr_class.split(" ");
         var tr_class_name = tr_class[0];
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
                question_time,
                "0"
            ]).node();
             rowNode.id = "question"+question_id;
             // wpisanie informacji o pytaniu do tablicy
             question_text_array.push({id:question_id,text:question_text,time:question_time,subject:category_name});
            //gdy pytanie jest powtórzone zaznacz na innny kolor
             if(jQuery.inArray(parseInt(question_id),question_repeat) != -1)
                 $(rowNode).css('background','black');
             // dodanie klasy z informacją że wiersz jest zaznaczony
             tr.removeClass('no_checked '+tr_class[1]).addClass('checked').addClass(tr_class[1]);
             //powiększ ilość pytań
             question_count++;
         }else {
             // usuń wiersz z tabeli pod modalem
             $('#question'+question_id).remove();
             //to samo w datatable, chyba zbędne wyżej
             table_all_guestion.row('#question'+question_id).remove().draw();
             //usunicie informacji o wierszu z tabeli
             removeFunction(question_text_array,"id",question_id);
             // zmiana flagi w klacie -> wyłączenie koloru
             tr.removeClass('checked '+tr_class[1]).addClass('no_checked').addClass(tr_class[1]);
             // zmniejsz ilość wybranych pytań
             question_count--;
         }
         // renderuj tabele
         table_all_guestion.draw();
         // zmień wratość wybranych pytań na stronie;
         $('#count_question').text(question_count);
     })
 })
</script>
@endsection
