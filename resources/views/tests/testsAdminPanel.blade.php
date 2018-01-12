@extends('layouts.main')
@section('content')
<style>
    .btn {
         outline: none !important;
         box-shadow: none !important;
    }

    .modal_column {
        width: 7%;
    }

    .category_column {
        width: 10%;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Panel zarządzania testami</h1>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
    <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<br />
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{URL::to('/tests_admin_panel')}}" id="category_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label><h3>Dodaj kategorię testów</h3></label>
                <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Kategoria..."/>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success btn-lg" id="category_submit" value="Dodaj"/>
            </div>
        </form>
    </div>
</div>
<br />

<div class="row">
    <div class="col-md-4">
        <input type="text" class="form-control" id="category_search"  placeholder="Wyszukaj..."/>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped type_table">
                <thead>
                    <tr>
                        <th style="width:5%">Lp.</th>
                        <th>Typ testu</th>
                        <th class="category_column">Ilość pytań</th>
                        <th class="category_column">Szczegóły</th>
                        <th class="category_column">Edycja</th>
                        <th class="category_column">ON/OFF</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($testCategory as $category)
                        @php($i++)
                        <tr name="{{$category->id}}">
                            <td style="width: 5%">{{$i}}</td>
                            <td>{{$category->name}}</td>
                            <td class="category_column">{{$category->questions->where('deleted', '=', 0)->count()}}</td>
                            <td class="category_column">
                                <button data-toggle="modal" class="btn btn-link categry_to_modal" data-target="#myModal" data-category_id="{{$category->id}}" title="Pokaż listę pytań">
                                    <span style="color:blue" class="glyphicon glyphicon-list">
                                    </span>
                                </button>
                            </td>
                            <td class="category_column">
                                <button class="btn btn-link edit_type" data-edit_type="{{$category->id}}">
                                    <span style="color:green" class="glyphicon glyphicon-pencil">
                                    </span>
                                </button>
                            </td>
                            <td class="category_column">
                                <button class="btn btn-link category_status" data-category_id="{{$category->id}}" data-category_status="{{$category->deleted}}">
                                    @if($category->deleted == 0)
                                        <span style="color:red" class="glyphicon glyphicon-remove"></span>
                                    @else
                                        <span style="color:green" class="glyphicon glyphicon-ok"></span>
                                    @endif
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 90%">

        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Pytania z kategorii: <span id="modal_category"></span></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-default" id="add_question"><span class="glyphicon glyphicon-plus"></span> Dodaj pytanie</button>
                    <div style="display: none" id="new_question">
                        <div class="form-group">
                            <label for="question_content">Treść pytania:</label>
                            <textarea rows="5" class="form-control" placeholder="Treść pytania" id="question_content" name="question_content"></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="question_time">Czas na pytanie (w minutach):</label>
                                <input name="question_time" id="question_time" type="number" class="form-control" placeholder="0" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success" id="question_ready"><span class="glyphicon glyphicon-plus"></span> Dodaj</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br /><br >
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Wyszukaj..." id="question_search"/>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table modal_table">
                    <thead>
                        <tr>
                            <th>Treść pytania</th>
                            <th class="modal_column">Czas (minuty)</th>
                            <th class="modal_column">Edycja</th>
                            <th class="modal_column">Usuń</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        </div>
        </div>

    </div>
</div>



@endsection

@section('script')
<script>

$('#category_submit').click((e) => {
    e.preventDefault();
    var category_name = $('#category_name').val();
    if (category_name == '') {
        swal('Podaj nazwę kategorii!')
        return false;
    } else {
        $('#category_form').submit();
    }
});

function edit_question_button(e) {
    var id = $(e).data('q_id');
    var check = $('.modal_table tr[name="' + id + '"]').find(' td:first textarea').html();

    if (check == undefined) {
        var question = $('.modal_table tr[name="' + id + '"]').find(' td:first').html();
        var rawHtml = '<textarea rows="5" id="edited_question" class="form-control">' + question +'</textarea>';
        $('.modal_table tr[name="' + id + '"]').find(' td:first').html(rawHtml);
        $('.modal_table tr[name="' + id + '"]').find(' td:nth-child(2) input').attr('readonly', false);
        $(e).find('span').removeClass('glyphicon-pencil').addClass('glyphicon-envelope');
        $("#myModal .btn-link").attr('disabled', true);
        $(e).attr('disabled', false);
    } else {
        var question = $('#edited_question').val();
        var newTime = $('.modal_table tr[name="' + id + '"]').find(' td:nth-child(2) input').val();
        var validation = true;
        if (question == '') {
            swal('Musisz podać treść pytania!')
            validation = false;
        }
        if (newTime == '') {
            swal('Musisz podać czas pytania!')
            validation = false;
        }
        if (newTime <= 0) {
            swal('Czas pytania musi wynosić minimum 1 minutę!')
            validation = false;
        }

        if (validation == true) {
            $.ajax({
                type:"POST",
                async: false,
                url: '{{ route('api.editTestQuestion') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    "question":question,
                    "question_id":id,
                    "newTime":newTime
                },
                success: function(response) {
                    if (response == 1) {
                        swal('Zmiany zapisano pomyślnie!')
                    } else {
                        swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!')
                    }
                }
            });
            $('.modal_table tr[name="' + id + '"]').find(' td:first').html(question);
            $(e).find('span').removeClass('glyphicon-envelope').addClass('glyphicon-pencil');
            $('.modal_table tr[name="' + id + '"]').find(' td:nth-child(2) input').attr('readonly', true);
            $(".btn-link").attr('disabled', false);
        }
    }
}

function deleteQuestion(e) {
    var id = $(e).data('q_id');

    swal({
        title: '',
        text: "Usunąć to pytanie?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak'
        }).then((result) => {
        if (result.value) {

            $.ajax({
                type: "POST",
                url: '{{ route('api.deleteTestQuestion') }}',
                data: {
                  "id":id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response == 1) {
                        swal('Pytanie zostało usunięte!')
                        $('tr[name="' + id + '"]').fadeOut(500);
                    } else {
                        swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!');
                    }
                }
            });
        }
        })
}

var type_is_edited = false;
$('.edit_type').click(function() {
    var id = $(this).data('edit_type');

    if (type_is_edited == false) {
        var question = $('.type_table tr[name="' + id + '"]').find(' td:nth-child(2)').html();
        var rawHtml = '<input type="text" id="edited_type" class="form-control" value="' + question +'">';
        $('.type_table tr[name="' + id + '"]').find(' td:nth-child(2)').html(rawHtml);
        $(this).find('span').removeClass('glyphicon-pencil').addClass('glyphicon-envelope');
        $(".btn-link").attr('disabled', true);
        $(this).attr('disabled', false);
        type_is_edited = true;
    } else {
        var question = $('#edited_type').val();
        if (question == '') {
            swal('Podaj nazwę kategorii!')
            return;
        }
        $.ajax({
            type:"POST",
            async: false,
            url: '{{ route('api.saveCategoryName') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                "new_name_category":question,
                "category_id":id
            },
            success: function(response) {
                if (response == 1) {
                    swal('Nazwa kategorii zmieniona pomyślnie!')
                } else {
                    swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }
        });
        $('.type_table tr[name="' + id + '"]').find(' td:nth-child(2)').html(question);
        $(this).find('span').removeClass('glyphicon-envelope').addClass('glyphicon-pencil');
        $(".btn-link").attr('disabled', false);
        type_is_edited = false;
    }
});

$('.category_status').click(function() {
    var id = $(this).data('category_id');
    var status = $(this).data('category_status');
    status = (status == 0) ? 1 : 0 ;
    var success = false;
    $.ajax({
        type:"POST",
        async: false,
        url: '{{ route('api.categoryStatusChange') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            "new_status":status,
            "category_id":id
        },
        success: function(response) {
            if (response == 1) {
                swal('Status kategorii zmieniony pomyślnie!')
                success = true;
            } else {
                swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!')
            }
        }
    });

    if (success == true) {
        $(this).data('category_status', status);
        if (status == 1) {
            $(this).find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok').css('color', 'green');
        } else {
            $(this).find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove').css('color', 'red');
        }
    }

});

var modal_category_id = null;
$('.categry_to_modal').click(function() {
    $('#myModal tbody').empty();
    modal_category_id = $(this).data('category_id');
});

$('#myModal').on('show.bs.modal', function() {
    $.ajax({
        type:"POST",
        async: false,
        url: '{{ route('api.showCategoryQuestions') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            "category_id":modal_category_id
        },
        success: function(response) {
           $('#modal_category').text(response[0].name);

            var modalHtml = '';
            $.each(response[1], function(key, value) {
                var questionTime = value.default_time / 60;
                modalHtml += '<tr name="' + value.id + '">';
                modalHtml += '<td>' + value.content + '</td>';
                modalHtml += '<td class="modal_column"><input type="number" class="form-control" value="' + questionTime + '" readonly></td>';
                modalHtml += '<td class="modal_column"><button class="btn btn-link" onclick="edit_question_button(this)" data-q_id="' + value.id + '"><span style="color:green" class="glyphicon glyphicon-pencil"></span></button></td>';
                modalHtml += '<td class="modal_column"><button class="btn btn-link" onclick="deleteQuestion(this)" data-q_id="' + value.id + '"><span style="color:red" class="glyphicon glyphicon-remove"></span></button></td>';
                modalHtml += '</tr>';
                 
           });
           $('#myModal tbody').append(modalHtml);
        }
    });
});

$('#myModal').on('hidden.bs.modal', function () {
    $.ajax({
        type:"POST",
        async: false,
        url: '{{ route('api.mainTableCounter') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            "category_id":modal_category_id
        },
        success: function(response) {
            if (response != null) {
                $('.type_table [name="' + modal_category_id + '"] td:nth-child(3)').text(response);
            }
        }
    });
})

$('#add_question').click(() => {
    $('#add_question').fadeOut(0);
    $('#new_question').fadeIn(500);
});

$('#question_ready').click(() => { 
    var content = $('#question_content').val();
    var question_time = $('#question_time').val();
    if (content == '') {
        swal('Podaj treść pytania!')
        return;
    }
    if (question_time == '') {
        swal('Podaj czas pytania!')
        return;
    }
    $.ajax({
        type:"POST",
        async: false,
        url: '{{ route('api.addTestQuestion') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            "category_id":modal_category_id,
            "content": content,
            "question_time":question_time
        },
        success: function(response) {
            if (response == 1) {
                swal('Pytanie zostało dodane!')
                $('#question_content').val('');
                $('#myModal').modal('toggle');
            } else {
                swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!')
                $('#myModal').modal('toggle');
            }
        }
    });
    $('#new_question').fadeOut(500);
    $('#add_question').fadeIn(500);
});

$(document).ready(function(){
    $("#question_search").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".modal_table tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

    $("#category_search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".type_table tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
  });
</script>
@endsection