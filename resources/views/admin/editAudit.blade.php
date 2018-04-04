@extends('layouts.main')
@section('content')
    <style>
        .active {
            display: block;
        }
        .inactive {
            display: none;
        }
    </style>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Edycja Audytów</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default panel-primary first-panel">
                <div class="panel-heading">
                    edycja
                </div>

                <div class="panel-body">
                    <form action="editAuditPage" method="post" id="formularz">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row row1">
                        <div class="col-md-6">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nagłówki</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($headers as $h)
                                    <tr>
                                        <td id="{{$h->id}}" class="headers">{{$h->name}}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kryteria</th>
                                    </tr>
                                </thead>
                                <tbody id="crit">
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <div class="row row2">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-info bt" id="firstHeaderAdd" style="width:49%;">Dodaj</button>
                            <button type="button" class="btn btn-info bt" id="firstHeaderDelete" style="width:49%;">Usuń</button>
                        </div>

                        <div class="col-md-6">
                            <button type="button" class="btn btn-info bt" id="firstCritAdd" style="width:49%;">Dodaj</button>
                            <button type="button"  class="btn btn-info bt" id="firstCritDelete" style="width:49%;">Usuń</button>
                        </div>
                    </div>

                    <div class="row row3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="headsSelect" class="form-control selectItem1">
                                    <option value="0">Wybierz</option>
                                    @foreach($headers as $h)
                                        <option value="{{$h->id}}">{{$h->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <select name="critSelect" class="form-control selectItem2">
                                <option value="0" id="firstOption">Wybierz</option>
                            </select>
                        </div>
                    </div>

                    <div class="row row4">
                        <div class="col-md-6">
                            <button class="btn btn-info btn-header" type="submit">Akceptuj</button>
                        </div>

                        <div class="col-md-6">
                            <button class="btn btn-info btn-crit" type="submit">Akceptuj</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

<script>
    document.addEventListener("DOMContentLoaded", function(event) {

        //selects each row
        var row2 = document.getElementsByClassName('row2')[0];
        var row3 = document.getElementsByClassName('row3')[0];
        var row4 = document.getElementsByClassName('row4')[0];

        //hide rows 2-4
        row2.classList.add('inactive');
        row3.classList.add('inactive');
        row4.classList.add('inactive');


        var headers = Array.from(document.querySelectorAll('.headers'));
        var buttons = Array.from(document.querySelectorAll('.bt'));
        var selects1 = Array.from(document.querySelectorAll('.selectItem1'));
        var selects2 = Array.from(document.querySelectorAll('.selectItem2'));
        var headerSubmit = document.querySelector('.btn-header');
        var critSubmit = document.querySelector('.btn-crit');

        var addingHeader;
        var addingCrit;
        var headerVal;
        var critVal;

        /**
         * Functions related to event listeners
         */

        //This function get data from database about criterions related to given heading and paste them into table and select
        function handleClick(e) {
            var tableBody = document.getElementById('crit');
            row2.classList.remove('inactive');
            tableBody.textContent = '';
            $.ajax({ //generate list of trainers from given location
                type: "POST",
                url: '{{ route('api.editAudit') }}',
                data: {
                    "header_id": e.target.id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    for(var i = 0; i < response.length; i++) {
                        var newItem = $('<tr><td class="crits" data-id="' + response[i].id + '">' + response[i].name.replace(/_/g , " ") + '</td></tr>');
                        var option = $('<option value="' + response[i].id + '">' + response[i].name + '</option>');
                        $('#crit').append(newItem);
                        $('#firstOption').after(option);
                    }
                }
            });
        }

        //function responsible for seting value of variable starting with "adding" which determine if item should be added or deleted
        function handleButton(e) {
            if(e.target.id == 'firstHeaderAdd') {
                addingHeader = true;
                row3.classList.remove('inactive');
            }
            else if(e.target.id == 'firstHeaderDelete') {
                addingHeader = false;
                row3.classList.remove('inactive');
            }

            else if(e.target.id == 'firstCritAdd') {
                addingCrit = true;
                row3.classList.remove('inactive');
            }
            else if(e.target.id == 'firstCritDelete') {
                addingCrit = false;
                row3.classList.remove('inactive');
            }
        }

        //AFTER SELECTION OF CRIT/HEADER SHOW SUBMIT BUTTONS
        function handleSelect1(e) {
            var selected = e.target;
            headerVal =  selected.options[selected.selectedIndex].value;
            row4.classList.remove('inactive');
        }

        function handleSelect2(e) {
            var selected = e.target;
            critVal =  selected.options[selected.selectedIndex].value;
            row4.classList.remove('inactive');
        }

        //Handle submit (add hidden input with info about adding/deleting)
        function handleHeaderSubmit(e) {
            e.preventDefault();
            var $input = $('<input type="hidden" name="addingHeader" value="' + addingHeader + '">');
            $('.btn-crit').after($input);
            document.querySelector('#formularz').submit();
        }

        function handleCritSubmit(e) {
            e.preventDefault();
            var $input = $('<input type="hidden" name="addingHeader" value="' + addingCrit + '">');
            $('.btn-crit').after($input);
            document.querySelector('#formularz').submit();
        }

        /*******************End Event listeners functions ***********************/


        headers.forEach(headers => headers.addEventListener('click', handleClick));
        buttons.forEach(button => button.addEventListener('click', handleButton));
        selects1.forEach(select => select.addEventListener('change', handleSelect1));
        selects2.forEach(select => select.addEventListener('change', handleSelect2));
        headerSubmit.addEventListener('click', handleHeaderSubmit);
        critSubmit.addEventListener('click', handleCritSubmit);

        //DODAC INPUTY DO WPROWADZENIA NAZW NOWYCH HEADEROW I CRITERIOW
    });
</script>
@endsection
