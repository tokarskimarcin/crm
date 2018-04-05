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
                                    @if($h->status == 1)
                                    <tr>
                                        <td id="{{$h->id}}" class="headers">{{$h->name}}</td>
                                        <td><span class="glyphicon glyphicon-minus gl-heads" data-headId="{{$h->id}}"></span></td>
                                    </tr>
                                    @endif
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
                        <div class="col-md-6 headerAddDel">
                            <button type="button" class="btn btn-info bt" id="firstHeaderAdd" style="width:49%;">Dodaj</button>
                            <button type="button" class="btn btn-info bt" id="firstHeaderDelete" style="width:49%;">Usuń</button>
                        </div>

                        <div class="col-md-6" critAddDel>
                            <button type="button" class="btn btn-info bt" id="firstCritAdd" style="width:49%;">Dodaj</button>
                            <button type="button"  class="btn btn-info bt" id="firstCritDelete" style="width:49%;">Usuń</button>
                        </div>
                    </div>

                        <div class="row row-between">
                            <div class="col-md-6">
                                <div class="form-group headerInpDiv">
                                    <input type="text" name="newHeaderName" class="form-control headerInp">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group critInpDiv">
                                    <input type="text" name="newCritName" class="form-control critInp">
                                </div>

                                <div class="form-group relatedHeaderDiv">
                                    <select name="relatedHeader" class="form-control headerRelated">
                                        <option value="0">Wybierz</option>
                                        @foreach($headers as $h)
                                            @if($h->status == 1)
                                            <option value="{{$h->id}}">{{$h->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    <div class="row row3">
                        <div class="col-md-6">
                            <div class="form-group headerSelect">
                                <select name="headsSelect" class="form-control selectItem1">
                                    <option value="0">Wybierz</option>
                                    @foreach($headers as $h)
                                        @if($h->status == 1)
                                        <option value="{{$h->id}}">{{$h->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group critSelect">
                                <select name="critSelect" class="form-control selectItem2">
                                    <option value="0" id="firstOption">Wybierz</option>
                                </select>
                            </div>

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

        /****************FIRST STAGE********************/
        //selects each row
        var row2 = document.getElementsByClassName('row2')[0];
        var row3 = document.getElementsByClassName('row3')[0];
        var row4 = document.getElementsByClassName('row4')[0];
        var rowBetween = document.getElementsByClassName('row-between')[0];

        //hide rows 2-4
        row2.classList.add('inactive');
        row3.classList.add('inactive');
        row4.classList.add('inactive');
        rowBetween.classList.add('inactive');


        var headers = Array.from(document.querySelectorAll('.headers'));
        var buttons = Array.from(document.querySelectorAll('.bt'));
        var selects1 = Array.from(document.querySelectorAll('.selectItem1'));
        var selects2 = Array.from(document.querySelectorAll('.selectItem2'));
        var headerSubmit = document.querySelector('.btn-header');
        var critSubmit = document.querySelector('.btn-crit');
        var headerInp = document.querySelector('.headerInp');
        var critInp = document.querySelector('.critInp');
        var headerSelect = document.querySelector('.headerSelect');
        var critSelect = document.querySelector('.critSelect');
        var headerInpDiv = document.querySelector('.headerInpDiv');
        var critInpDiv = document.querySelector('.critInpDiv');
        var relatedHeader = document.querySelector('.relatedHeaderDiv');
        var selectRelated = document.querySelector('.headerRelated');
        var critGl = [];
        var headGl = Array.from(document.querySelectorAll('.gl-heads'));


        var addingHeader;
        var addingCrit;
        var headerVal;
        var critVal;


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
                        var newItem = $('<tr><td class="crits" data-id="' + response[i].id + '">' + response[i].name.replace(/_/g , " ") + '</td><td><span class="glyphicon glyphicon-minus gl-crits" data-critId="' + response[i].id + '"></span></td></tr>');
                        var option = $('<option value="' + response[i].id + '">' + response[i].name + '</option>');
                        $('#crit').append(newItem);
                        $('#firstOption').after(option);

                    }
                }
            });
        }

        function critGlHandle(e) {
                swal({
                    title: 'Jestes pewien?',
                    text: "Po potwierdzeniu, brak możliwości cofnięcia zmian!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Usuń!'
                }).then((result) => {
                    if (result.value) {
                    addingCrit = false;
                    var $input = $('<input type="hidden" name="addingCrit" value="' + addingCrit + '">');
                    $('.btn-crit').after($input);
                    var $input2 = $('<input type="hidden" name="cID" value="' + e.target.dataset.critid + '">');
                    $('.btn-crit').after($input2);
                    document.querySelector('#formularz').submit();
                    swal(
                        'Usunięte!',
                        'Kryterium zostało usunięte',
                        'Sukces'
                    )
                }
            });
        }

        function headGlHandle(e) {
                swal({
                    title: 'Jestes pewien?',
                    text: "Po potwierdzeniu, brak możliwości cofnięcia zmian!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Usuń!'
                }).then((result) => {
                    if (result.value) {
                    addingHeader = false;
                    var $input = $('<input type="hidden" name="addingHeader" value="' + addingHeader + '">');
                    $('.btn-crit').after($input);
                    var $input2 = $('<input type="hidden" name="hID" value="' + e.target.dataset.headid + '">');
                    $('.btn-crit').after($input2);
                    document.querySelector('#formularz').submit();
                    swal(
                        'Usunięte!',
                        'Kryterium zostało usunięte',
                        'Sukces'
                    )
                }
            });

        }

        $( document ).ajaxComplete(function() {
            critGl = Array.from(document.querySelectorAll('.gl-crits'));
            critGl.forEach(gl => gl.addEventListener('click', critGlHandle));
        });

        headGl.forEach(gl => gl.addEventListener('click', headGlHandle));

        headers.forEach(headers => headers.addEventListener('click', handleClick));
        /**************************END FIRST STAGE******************************/

        /****************************SECOND STAGE*******************************/
        //function responsible for seting value of variable starting with "adding" which determine if item should be added or deleted
        function handleButton(e) {
            if(e.target.id == 'firstHeaderAdd') {
                addingHeader = true;
                rowBetween.classList.remove('inactive');
                critInpDiv.classList.add('inactive');
                critSelect.classList.add('inactive');
                headerInpDiv.classList.remove('inactive');
                headerSelect.classList.add('inactive');
                relatedHeader.classList.add('inactive');
                row3.classList.remove('inactive');
            }
            else if(e.target.id == 'firstHeaderDelete') {
                addingHeader = false;
                row3.classList.remove('inactive');
                headerSelect.classList.remove('inactive');
                critInpDiv.classList.add('inactive');
                critSelect.classList.add('inactive');
                rowBetween.classList.add('inactive');
                relatedHeader.classList.add('inactive');
            }

            else if(e.target.id == 'firstCritAdd') {
                addingCrit = true;
                rowBetween.classList.remove('inactive');
                critSelect.classList.add('inactive');
                headerInpDiv.classList.add('inactive');
                headerSelect.classList.add('inactive');
                critInpDiv.classList.remove('inactive');
                relatedHeader.classList.remove('inactive');
                row3.classList.remove('inactive');
            }
            else if(e.target.id == 'firstCritDelete') {
                addingCrit = false;
                row3.classList.remove('inactive');
                critSelect.classList.remove('inactive');
                headerInpDiv.classList.add('inactive');
                headerSelect.classList.add('inactive');
                relatedHeader.classList.add('inactive');
                rowBetween.classList.add('inactive');
            }
        }

        buttons.forEach(button => button.addEventListener('click', handleButton));

        /***********************END SECOND STAGE ***************************/

        /*************************THIRD STAGE*******************************/
        //AFTER SELECTION OF CRIT/HEADER SHOW SUBMIT BUTTONS
        function handleSelect1(e) {
            var selected = e.target;
            headerVal =  selected.options[selected.selectedIndex].value;
            row4.classList.remove('inactive');
            if(headerVal == '0' || headerVal == 0) {
                row4.classList.add('inactive');
            }
        }

        function handleSelect2(e) {
            var selected = e.target;
            critVal =  selected.options[selected.selectedIndex].value;
            row4.classList.remove('inactive');
            if(critVal == '0' || critVal == 0) {
                row4.classList.add('inactive');
            }
        }

        //Show/hide akceptuj button
        function headSelectChange(e) {
            row4.classList.remove('inactive');
            if(e.target.value == '') {
                row4.classList.add('inactive');
            }
        }

        function critSelectChange(e) {
            row4.classList.remove('inactive');
            if(e.target.value == '') {
                row4.classList.add('inactive');
            }
        }

        headerInp.addEventListener('input', headSelectChange);
        critInp.addEventListener('input', critSelectChange);

        selects1.forEach(select => select.addEventListener('change', handleSelect1));
        selects2.forEach(select => select.addEventListener('change', handleSelect2));

        /*****************************END THIRD STAGE************************/

        /*******************************FOURTH STAGE*************************/
        //Handle submit (add hidden input with info about adding/deleting)
        function handleHeaderSubmit(e) {
            e.preventDefault();
            var $input = $('<input type="hidden" name="addingHeader" value="' + addingHeader + '">');
            $('.btn-crit').after($input);

            if(addingHeader) {
                if($('.headerInp').val() != null && $('.headerInp').val() != '') {
                    document.querySelector('#formularz').submit();
                }
            }
            else {
                if($('.selectItem1').val() != 0) {
                    document.querySelector('#formularz').submit();
                }
            }
        }

        function handleCritSubmit(e) {
            e.preventDefault();
            var $input = $('<input type="hidden" name="addingCrit" value="' + addingCrit + '">');
            $('.btn-crit').after($input);

            if(addingCrit) {
                if($('.critInp').val() != null && $('.critInp').val() != '') {
                    if($('.headerRelated').val() != 0) {
                        document.querySelector('#formularz').submit();

                    }
                    else {
                        swal('Wybierz nagłówek dla którego ma być dodane nowe kryterium');
                    }
                }
            }
            else {
                if($('.selectedItem2').val() != 0) {
                    document.querySelector('#formularz').submit();

                }
            }
        }



        headerSubmit.addEventListener('click', handleHeaderSubmit);
        critSubmit.addEventListener('click', handleCritSubmit);

        /****************************END FOURTH STAGE************************/
    });
</script>
@endsection
