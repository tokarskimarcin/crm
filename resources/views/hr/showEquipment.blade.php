@extends('layouts.main')
@section('content')


{{--Header page --}}
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Sprzęt Firmowy</h1>
    </div>
</div>
@if (Session::has('message_ok'))
    <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
  <div class="col-lg-12">
    <a class="btn btn-default" href="add_equipment/1">Dodaj laptop</a>
    <a class="btn btn-default" href="add_equipment/2">Dodaj tablet</a>
    <a class="btn btn-default" href="add_equipment/3">Dodaj telefon</a>
    <a class="btn btn-default" href="add_equipment/4">Dodaj kartę SIM</a>
    <a class="btn btn-default" href="add_equipment/5">Dodaj Monitor</a>
    <a class="btn btn-default" href="add_equipment/6">Dodaj drukarkę</a>
  </div>
</div>
<br/>
<ul class="nav nav-tabs">
  <li class="menu_item active" id="menu_laptop"><a href="#">Laptopy</a></li>
  <li class="menu_item" id="menu_tablet"><a href="#">Tablety</a></li>
  <li class="menu_item" id="menu_phone"><a href="#">Telefony</a></li>
  <li class="menu_item" id="menu_sim_card"><a href="#">Karty SIM</a></li>
  <li class="menu_item" id="menu_monitor"><a href="#">Monitory</a></li>
  <li class="menu_item" id="menu_printer"><a href="#">Drukarki</a></li>
</ul>

<br />

<div class="table-responsive" id="div_laptop">
    <table id="laptop" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
      <tr>
          <td>Model</td>
          <td>Numer seryjny</td>
          <td>Procesor</td>
          <td>Ram</td>
          <td>Dysk</td>
          <td>Opis</td>
          <td>Oddział</td>
          <td>Pracownik</td>
          <td>Akcja</td>
      </tr>
    </thead>
    <tbody>

    </tbody>
    </table>
</div>
<div class="table-responsive" style="display: none" id="div_tablet">
    <table id="tablet" class="table table-striped table-bordered" cellspacing="0" width="100%" >
      <thead>
      <tr>
          <td>Model</td>
          <td>Numer seryjny</td>
          <td>Imei</td>
          <td>Modem 3G</td>
          <td>Opis</td>
          <td>Oddział</td>
          <td>Pracownik</td>
          <td>Akcja</td>
      </tr>
    </thead>
    <tbody>

    </tbody>
    </table>
</div>
<div class="table-responsive" style="display: none" id="div_phone">
    <table id="phone" class="table table-striped table-bordered" cellspacing="0" width="100%" >
      <thead>
      <tr>
          <td>Model</td>
          <td>Numer IMEI</td>
          <td>Ładowarka</td>
          <td>Pudełko</td>
          <td>Opis</td>
          <td>Oddział</td>
          <td>Pracownik</td>
          <td>Akcja</td>
      </tr>
    </thead>
    <tbody>

    </tbody>
    </table>
</div>
<div class="table-responsive" style="display: none" id="div_sim_card">
    <table id="sim_card" class="table table-striped table-bordered" cellspacing="0" width="100%" >
      <thead>
      <tr>
          <td>Typ</td>
          <td>Numer Telefonu</td>
          <td>PIN</td>
          <td>PUK</td>
          <td>Internet</td>
          <td>Opis</td>
          <td>Oddział</td>
          <td>Pracownik</td>
          <td>Akcja</td>
      </tr>
    </thead>
    <tbody>

    </tbody>
    </table>
</div>
<div class="table-responsive" style="display: none" id="div_monitor">
    <table id="monitor" class="table table-striped table-bordered" cellspacing="0" width="100%" >
      <thead>
        <tr>
            <td>Model</td>
            <td>Numer seryjny</td>
            <td>Kabel Sygnałowy</td>
            <td>Kabel zasilający</td>
            <td>Opis</td>
            <td>Oddział</td>
            <td>Pracownik</td>
            <td>Akcja</td>
        </tr>
    </thead>
    <tbody>

    </tbody>
    </table>
</div>
<div class="table-responsive" style="display: none" id="div_printer">
    <table id="printer" class="table table-striped table-bordered" cellspacing="0" width="100%" >
      <thead>
        <tr>
            <td>Model</td>
            <td>Numer seryjny</td>
            <td>Opis</td>
            <td>Oddział</td>
            <td>Pracownik</td>
            <td>Akcja</td>
        </tr>
    </thead>
    <tbody>

    </tbody>
    </table>
</div>
@endsection
@section('script')
<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.select.min.js')}}"></script>

<script>
$('.edit_me').on( 'click',function () {
        var data = table.row( $(this).parents('tr') ).data();
        alert( data[0] +"'s salary is: "+ data[ 5 ] );
    } );

table = $('#laptop').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowLaptop') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {

        }
    }, "columns": [

        {"data": 'model'},
        {"data": 'serial_code'},
        {"data": 'laptop_processor'},
        {"data": 'laptop_ram'},
        {"data": 'laptop_hard_drive'},
        {"data": 'description'},
        {"data": function (data, type, dataToSet) {
            if(data.department_info_id != 0 && data.department_info_id != null)
                return data.dep_name + " " + data.dep_name_type;
            else return 'Brak'
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
            if(data.id_user != 0 && data.first_name != 'null' && data.first_name != null)
                return data.first_name + " " + data.last_name;
            else return 'Brak'
        }, "name": "id_user"},
        {"data": function (data, type, dataToSet) {
            return "<a class='btn btn-default' href={{URL::to('/edit_equipment/')}}/" + data.id + ">Edytuj</a>";
        }, "name": "id_user"},
    ],

});

table = $('#tablet').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowTablet') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {


        }
    }, "columns": [

        {"data": 'model'},
        {"data": 'serial_code'},
        {"data": 'imei'},
        {"data": function (data, type, dataToSet) {
            if(data.id_user = 1)
                return 'Tak';
            else return 'Nie'
        }, "name": "tablet_modem"},
        {"data": 'description'},
        {"data": function (data, type, dataToSet) {
            if(data.department_info_id != 0 && data.department_info_id != null)
                return data.dep_name + " " + data.dep_name_type;
            else return 'Brak'
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
             if(data.id_user != 0 && data.first_name != 'null' && data.first_name != null)
                return data.first_name + " " + data.last_name;
            else return 'Brak'
        }, "name": "id_user"},
        {"data": function (data, type, dataToSet) {
            return "<a class='btn btn-default' href={{URL::to('/edit_equipment/')}}/" + data.id + ">Edytuj</a>";
        }, "name": "id_user"},
    ],

});

table = $('#phone').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowPhone') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {


        }
    }, "columns": [

        {"data": 'model'},
        {"data": 'imei'},
        {"data": function (data, type, dataToSet) {
            if(data.id_user = 1)
                return 'Tak';
            else return 'Nie'
        }, "name": "power_cable"},
        {"data": function (data, type, dataToSet) {
            if(data.id_user = 1)
                return 'Tak';
            else return 'Nie'
        }, "name": "phone_box"},
        {"data": 'description'},
        {"data": function (data, type, dataToSet) {
            if(data.department_info_id != 0 && data.department_info_id != null)
                return data.dep_name + " " + data.dep_name_type;
            else return 'Brak'
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
             if(data.id_user != 0 && data.first_name != 'null' && data.first_name != null)
                return data.first_name + " " + data.last_name;
            else return 'Brak'
        }, "name": "id_user"},
        {"data": function (data, type, dataToSet) {
            return "<a class='btn btn-default' href={{URL::to('/edit_equipment/')}}/" + data.id + ">Edytuj</a>";
        }, "name": "id_user"},
    ],

});
table = $('#sim_card').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowSimCard') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {


        }
    }, "columns": [

        {"data": function (data, type, dataToSet) {
            if(data.id_user = 1)
                return 'Abonament';
            else return 'Prepaid'
        }, "name": "sim_type"},
        {"data": 'sim_number_phone'},
        {"data": 'sim_pin'},
        {"data": 'sim_puk'},
        {"data": function (data, type, dataToSet) {
            if(data.id_user = 1)
                return 'Tak';
            else return 'Nie'
        }, "name": "sim_net"},
        {"data": 'description'},
        {"data": function (data, type, dataToSet) {
            if(data.department_info_id != 0 && data.department_info_id != null)
                return data.dep_name + " " + data.dep_name_type;
            else return 'Brak'
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
             if(data.id_user != 0 && data.first_name != 'null' && data.first_name != null)
                return data.first_name + " " + data.last_name;
            else return 'Brak'
        }, "name": "id_user"},
        {"data": function (data, type, dataToSet) {
            return "<a class='btn btn-default' href={{URL::to('/edit_equipment/')}}/" + data.id + ">Edytuj</a>";
        }, "name": "id_user"},
    ],

});
table = $('#monitor').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowMonitor') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {


        }
    }, "columns": [

        {"data": 'model'},
        {"data": 'serial_code'},
        {"data": 'signal_cable'},
        {"data": 'power_cable'},
        {"data": 'description'},
        {"data": function (data, type, dataToSet) {
            if(data.department_info_id != 0 && data.department_info_id != null)
                return data.dep_name + " " + data.dep_name_type;
            else return 'Brak'
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
             if(data.id_user != 0 && data.first_name != 'null' && data.first_name != null)
                return data.first_name + " " + data.last_name;
            else return 'Brak'
        }, "name": "id_user"},
        {"data": function (data, type, dataToSet) {
            return "<a class='btn btn-default' href={{URL::to('/edit_equipment/')}}/" + data.id + ">Edytuj</a>";
        }, "name": "id_user"},
    ],

});

table = $('#printer').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowPrinter') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {


        }
    }, "columns": [

        {"data": 'model'},
        {"data": 'serial_code'},
        {"data": 'description'},
        {"data": function (data, type, dataToSet) {
            if(data.department_info_id != 0 && data.department_info_id != null)
                return data.dep_name + " " + data.dep_name_type;
            else return 'Brak'
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
             if(data.id_user != 0 && data.first_name != 'null' && data.first_name != null)
                return data.first_name + " " + data.last_name;
            else return 'Brak'
        }, "name": "id_user"},
        {"data": function (data, type, dataToSet) {
            return "<a class='btn btn-default' href={{URL::to('/edit_equipment/')}}/" + data.id + ">Edytuj</a>";
        }, "name": "id_user"},
    ],

});
      $('.menu_item').on('click', function(){
          var id = this.id;

          function deletePrevious() {
              $("#menu_laptop, #menu_tablet, #menu_phone, #menu_sim_card, #menu_monitor, #menu_printer").removeClass('active');
              $("#div_laptop, #div_tablet, #div_phone, #div_sim_card, #div_monitor, #div_printer").fadeOut(0);
          }

          if(id == "menu_laptop") {
              deletePrevious();
              $("#menu_laptop").addClass('active');
              $("#div_laptop").fadeIn(0);
          }

          if(id == "menu_tablet") {
              deletePrevious();
              $("#menu_tablet").addClass('active');
              $("#div_tablet").fadeIn(0);
          }

          if(id == "menu_phone") {
              deletePrevious();
              $("#menu_phone").addClass('active');
              $("#div_phone").fadeIn(0);
          }

          if(id == "menu_sim_card") {
              deletePrevious();
              $("#menu_sim_card").addClass('active');
              $("#div_sim_card").fadeIn(0);
          }

          if(id == "menu_monitor") {
              deletePrevious();
              $("#menu_monitor").addClass('active');
              $("#div_monitor").fadeIn(0);
          }

          if(id == "menu_printer") {
              deletePrevious();
              $("#menu_printer").addClass('active');
              $("#div_printer").fadeIn(0);
          }

      });

</script>

@endsection
