@extends('layouts.main')
@section('content')
<style>
    .btn {
         outline: none !important;
         box-shadow: none !important;
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
        <form method="POST" action="{{URL::to('/tests_admin_panel')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label><h3>Dodaj kategorię testów</h3></label>
                <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Kategoria..."/>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success btn-lg" value="Dodaj"/>
            </div>
        </form>
    </div>
</div>
<br />

<div class="row">
    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table table-striped type_table">
                <thead>
                    <tr>
                        <th style="width:10%">Lp.</th>
                        <th>Typ testu</th>
                        <th style="width:10%">Ilość pytań</th>
                        <th style="width:10%">Szczegóły</th>
                        <th style="width:10%">Edycja</th>
                        <th style="width:10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($testCategory as $category)
                        @php($i++)
                        <tr name="{{$category->id}}">
                            <td>{{$i}}</td>
                            <td>{{$category->name}}</td>
                            <td>{{$category->questions->count()}}</td>
                            <td>
                                <button data-toggle="modal" class="btn btn-link categry_to_modal" data-target="#myModal" data-category_id="{{$category->id}}" title="Pokaż listę pytań">
                                    <span style="color:blue" class="glyphicon glyphicon-list">
                                    </span>
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-link edit_type" data-edit_type="{{$category->id}}">
                                    <span style="color:green" class="glyphicon glyphicon-pencil">
                                    </span>
                                </button>
                            </td>
                            <td>
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
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Pytania z kategorii: <span id="modal_category"></span></h4>
            <input type="hidden" id="modal_id" value=""/>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-default" id="add_question"><span class="glyphicon glyphicon-plus"></span> Dodaj pytanie</button>
                    <div style="display: none" id="new_question">
                        <div class="form-group">
                            <textarea rows="5" class="form-control" placeholder="Treść pytania"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" id="question_ready"><span class="glyphicon glyphicon-plus"></span> Dodaj</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table modal_table">
                    <thead>
                        <tr>
                            <th>Treść pytania</th>
                            <th style="width:10%">Edycja</th>
                            <th style="width:10%">Usuń</th>
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

$('#add_question').click(() => {
    $('#add_question').fadeOut(0);
    $('#new_question').fadeIn(500);
});

$('#question_ready').click(() => {
    var category = $().val();
    $('#new_question').fadeOut(500);
    $('#add_question').fadeIn(500);
});

$('.edit_question_button').click(function() {
    var id = $(this).data('q_id');
    var check = $('.modal_table tr[name="' + id + '"]').find(' td:first textarea').html();

    if (check == undefined) {
        var question = $('.modal_table tr[name="' + id + '"]').find(' td:first').html();
        var rawHtml = '<textarea rows="5" id="edited_question" class="form-control">' + question +'</textarea>';
        $('.modal_table tr[name="' + id + '"]').find(' td:first').html(rawHtml);
        $(this).find('span').removeClass('glyphicon-pencil').addClass('glyphicon-envelope');
        $(".btn-link").attr('disabled', true);
        $(this).attr('disabled', false);
    } else {
        var question = $('#edited_question').val();
        //tutaj pojdzie ajax
        $('.modal_table tr[name="' + id + '"]').find(' td:first').html(question);
        $(this).find('span').removeClass('glyphicon-envelope').addClass('glyphicon-pencil');
        $(".btn-link").attr('disabled', false);
    }
});

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
                modalHtml += '<tr name="' + value.id + '"><td>' + value.content;
                modalHtml += '</td><td><button class="btn btn-link edit_question_button" data-q_id="' + value.id + '"><span style="color:green" class="glyphicon glyphicon-pencil"></span></button></td><td>';
                modalHtml += '<button class="btn btn-link"><span style="color:red" class="glyphicon glyphicon-remove"></span></button></td></tr>';
           });
           $('#myModal tbody').append(modalHtml);
        }
    });
});

</script>
@endsection
