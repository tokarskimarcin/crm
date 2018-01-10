@extends('layouts.main')
@section('content')
<style>
    .dropdown-menu{
        left: 0px;
    }
    .checked{
        background: red;
    }
    .no_checked{
        background: white;
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
                                <p><button  data-toggle="modal" data-target="#myModal2">FUKO</button> <br></p>
                                <p><button  data-toggle="modal" data-target="#myModal2">COACHING</button> <br></p>
                                <p><button  data-toggle="modal" data-target="#myModal2">KOORDYNOWANIE</button> <br></p>
                            </div>
                        </div>


                        <div class="col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">Test dla: </div>
                                    <select class="selectpicker" style="left: 0px" name="link_privilages[]" title="Brak wyznaczonych pracowników" multiple data-actions-box="true">
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski</option>
                                        <option>Janusz Kowalski</option>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<!-- Sekcja z modalami -->


<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal2" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lista Pytań COACHING</h4>
                </div>
                <div class="modal-body">
                    <table id="question_table" class="table">
                        <thead>
                        <tr>
                            <th>Treść</th>
                            <th>Czas</th>
                            <th>Akcja</th>
                        <tr>
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
    var question_text_array = [];
    var question_time_array = [];
    console.log(question_text_array);
 $(document).ready( function () {

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


     $(".button_question_choice").on('click',function () {
         var tr = $(this).closest('tr');
         var tr_class_name = tr.attr('class');
         var question_text = tr.find('td.question_text').text();
         var question_id = tr.attr('id');
         var question_time = tr.find('td input').val();

         if(tr_class_name == 'no_checked')
         {
             question_text_array.push({id:question_id,text:question_text,time:question_time});
             tr.removeClass('no_checked').addClass('checked');
         }else {
             removeFunction(question_text_array,"id",question_id);
             tr.removeClass('checked').addClass('no_checked');
         }
         console.log(question_text_array);



     })
 })
</script>
@endsection
