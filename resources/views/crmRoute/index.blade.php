@extends('layouts.main')
@section('style')

@endsection
@section('content')


{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Tworzenie Tras</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Tworzenie Tras
                </div>
                <div class="panel-body">
                        @include('crmRoute.client')
                        @include('crmRoute.routes')

                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    $('.form_date').datetimepicker({
        language:  'pl',
        autoclose: 1,
        minView : 2,
        pickTime: false
    });


    $(document).ready(function() {

        function clear_modal() {
            // document.getElementsByName('client_name')[0]
            // document.getElementsByName('client_phone')[0].value ='';
            // document.getElementsByName('client_type')[0].value ='Wybierz';
            // console.log(document.getElementsByName('client_name')[0]);
        }
        function edit_client(e) {
            var client_id = e.getAttribute('data-id');
            var tr_line = e.closest('tr');
            var tr_line_name = tr_line.getElementsByClassName('client_name')[0].textContent;
            var tr_line_phone = tr_line.getElementsByClassName('client_phone')[0].textContent;
            var tr_line_type = tr_line.getElementsByClassName('client_type')[0].textContent;
            clear_modal();
            $('#Modal_Client').modal('show');
            console.log(tr_line);
        }
        function save_client(e){
            alert('Klient dodany');
            $('#Modal_Client').modal('hide');
        }
        $(document).ready(function(){
            var table_client = $('#table_client').DataTable({
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"columns":[
                    {"width": "10%"},
                    {"width": "5%"},
                    {"width": "10%"},
                    {"width": "10%"},
                    {"width": "1%"},
                ]
            });
        });

        function Stack() {
            let items = [];
            this.push = function(element) { //add element to stack
                items.push(element);
            };
            this.pop = function() { //remove element from stack
                return items.pop();
            };
            this.peek = function() { //what is top element
                return items[items.length - 1];
            };
            this.isEmpty = function() { //sprawdza czy jest pusta true = tak/ false = nie
                return items.length == 0;
            };
            this.size = function() {  // return lenght of queue
                return items.length;
            };
            this.print = function() { //print queue elements
                console.log(items.toString());
            };
            this.clear = function() {
                items = [];
            }
        }

        /**
         *Ta funkcja tworzy nowy route
         */
        function create_new_route() {
            let number_of_route = route_stack.size() + 1;

            newElement = document.createElement('div');
            newElement.className = 'routes-container';
            newElement.innerHTML = '        <div class="row">\n' +
                    '<div class="button_section button_section_gl_nr' + number_of_route + '">' +
                    '<span class="glyphicon glyphicon-remove" id="remove-route"></span>' +
                    '</div>' +
                '        <header>Trasa #' + number_of_route + '</header>\n' +
                '\n' +
                '            <div class="col-md-4">\n' +
                '                <div class="form-group">\n' +
                '                    <label for="woj' + number_of_route + '">Województwo</label>\n' +
                '                    <select name="woj' + number_of_route + '" id="woj' + number_of_route + '" class="form-control">\n' +
                '                        <option value="0">Wybierz</option>\n' +
                '                        <option value="1">Lubelskie</option>\n' +
                '                        <option value="2">Mazowieckie</option>\n' +
                '                    </select>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '\n' +
                '            <div class="col-md-4">\n' +
                '                <div class="form-group">\n' +
                '                    <label for="city' + number_of_route + '">Miasto</label>\n' +
                '                    <select name="city' + number_of_route + '" id="city' + number_of_route + '" class="form-control">\n' +
                '                        <option value="0">Wybierz</option>\n' +
                '                        <option value="1">Lublin</option>\n' +
                '                        <option value="2">Świdnik</option>\n' +
                '                    </select>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '\n' +
                '            <div class="col-md-4">\n' +
                '                <div class="form-group">\n' +
                '                    <label for="karencja' + number_of_route + '">Karencja</label>\n' +
                '                    <select name="karencja' + number_of_route + '" id="karencja' + number_of_route + '" class="form-control">\n' +
                '                        <option>Wybierz</option>\n' +
                '                        <option value="0">0</option>\n' +
                '                        <option value="1">1</option>\n' +
                '                        <option value="2">2</option>\n' +
                '                        <option value="3">3</option>\n' +
                '                    </select>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '\n' +
                '            <div class="form-group">\n' +
                '                <label for="date">Data:</label>\n' +
                '                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">\n' +
                '                    <input class="form-control" name="start_date' + number_of_route + '" type="text">\n' +
                '                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '\n' +
                '            <div class="col-lg-12 button_section button_section_nr' + number_of_route + '">\n' +
                '                \n' +
                '                <input type="button" class="btn btn-success" id="add_new_routes" value="Zapisz!" style="width:100%;">\n' +
                '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_route" value="Dodaj nową trasę" style="width:100%;margin-top:1em;margin-bottom:1em;">' +
                '            </div>\n' +
                '        </div>';
            return newElement;
        }

        /**
         * Ta funkcja usuwa wszystkie buttony z dotychczasowych routów
         */
        function clear_button_section() {
            let button_section = Array.from(document.getElementsByClassName('button_section'));
            button_section.forEach(function(section) {
                section.textContent = '';
            });
        }

        /**
         * Ta funkcja dodaje nowy route
         */
        function add_new_route() {
            let new_route = create_new_route(); //otrzymujemy nowy formularz
            clear_button_section();
            route_stack.push(new_route); //dodajemy go do stosu
            let main_container = document.querySelector('.routes-wrapper'); //zaznaczamy główny container
            main_container.appendChild(new_route); //dodajemy nowy element jako ostatnie dziecko

            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false
            });
        }

        function click_button_handler(e) {
            if(e.target.id == 'add_new_route') {
                add_new_route();
            }
            if(e.target.id == "remove-route") {
                let last_route = document.getElementsByClassName('routes-container')[route_stack.size() - 1];
                let route_wrapper = document.getElementsByClassName('routes-wrapper')[0];
                route_wrapper.removeChild(last_route); //deleta last route

                let button_div_class_name = 'button_section_nr' + (route_stack.size() - 1);
                let button_div_gl_class_name = 'button_section_gl_nr' + (route_stack.size() - 1);
                let pre_last_button_div = document.getElementsByClassName(button_div_class_name)[0]; //select previous route's buttons div
                let pre_last_button_gl_div = document.getElementsByClassName(button_div_gl_class_name)[0]; //select previous route's buttons div
                pre_last_button_div.innerHTML = '                <input type="button" class="btn btn-success" id="add_new_routes" value="Zapisz!" style="width:100%;">\n' +
                '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_route" value="Dodaj nową trasę" style="width:100%;margin-top:1em;margin-bottom:1em;">';
                pre_last_button_gl_div.innerHTML = '<span class="glyphicon glyphicon-remove" id="remove-route"></span>';

                route_stack.pop();
            }
        }

        let route_stack = new Stack();

        add_new_route();

        let routes_wrapper = document.getElementsByClassName('routes-wrapper')[0];
        routes_wrapper.addEventListener('click', click_button_handler);

    });

</script>
@endsection
