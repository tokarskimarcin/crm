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

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                  <br />
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <a class="btn btn-default" href="add_equipment/1">Dodaj laptop</a>
                                      <a class="btn btn-default" href="add_equipment/3">Dodaj tablet</a>
                                      <a class="btn btn-default" href="add_equipment/2">Dodaj telefon</a>
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
                                  <div class="table-responsive"> 
                                  <table class="table table-bordered" id="laptop">
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
                                      @foreach($laptops as $equipment)
                                        <tr>
                                              <td>{{$equipment->model}}</td>
                                              <td>{{$equipment->serial_code}}</td>
                                              <td>{{$equipment->laptop_processor}}</td>
                                              <td>{{$equipment->laptop_ram}}</td>
                                              <td>{{$equipment->laptop_hard_drive}}</td>
                                              <td>{{$equipment->description}}</td>
                                              @if($equipment->department_info_id != -1 && $equipment->department_info_id != 0)
                                                  <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                              @else
                                                  <td>Brak</td>
                                              @endif
                                              @if($equipment->user != null)
                                                  <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                              @else
                                                  <td>Brak</td>
                                              @endif
                                              <td>
                                                  <a href="edit_equipment/{{$equipment->id}}" class="btn btn-info">Edytuj</a>
                                              </td>
                                          </tr>
                                      @endforeach
                                      </table>
                                      </div>
                                      <div class="table-responsive">
                                      <table class="table table-bordered" id="tablet" style="display: none">
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
                                          @foreach($tablet as $equipment)
                                              <td data-model="{{$equipment->model}}">{{$equipment->model}}</td>
                                              <td>{{$equipment->serial_code}}</td>
                                              <td>{{$equipment->imei}}</td>
                                              @if($equipment->tablet_modem == 1)
                                                  <td>Tak</td>
                                              @else
                                                  <td>Nie</td>
                                              @endif
                                              <td>{{$equipment->description}}</td>
                                              @if($equipment->department_info_id != 0)
                                                  <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                              @else
                                                  <td>Brak</td>
                                              @endif
                                              @if($equipment->user != null)
                                                  <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                              @else
                                                  <td>Brak</td>
                                              @endif
                                              <td>
                                                  <a href="edit_equipment/{{$equipment->id}}" class="btn btn-info">Edytuj</a>
                                              </td>
                                              </tr>
                                          @endforeach
                                          </table>
                                          </div>
                                          <div class="table-responsive">
                                          <table class="table table-bordered" id="phone" style="display: none">
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
                                              @foreach($phone as $equipment)
                                                  <td>{{$equipment->model}}</td>
                                                  <td>{{$equipment->imei}}</td>
                                                  @if($equipment->power_cable == 1)
                                                      <td>Tak</td>
                                                  @else
                                                      <td>Nie</td>
                                                  @endif
                                                  @if($equipment->phone_box == 1)
                                                      <td>Tak</td>
                                                  @else
                                                      <td>Nie</td>
                                                  @endif
                                                  <td>{{$equipment->description}}</td>
                                                  @if($equipment->department_info_id != 0)
                                                      <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                  @else
                                                      <td>Brak</td>
                                                  @endif
                                                  @if($equipment->user != null)
                                                      <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                  @else
                                                      <td>Brak</td>
                                                  @endif
                                                  <td>
                                                      <a href="edit_equipment/{{$equipment->id}}" class="btn btn-info">Edytuj</a>
                                                  </td>
                                                </tr>
                                              @endforeach
                                          </table>
                                          </div>
                                          <div class="table-responsive">
                                          <table class="table table-bordered" id="sim_card" style="display: none">
                                              <tr>
                                                  <td>Typ</td>
                                                  <td>Numer Telefonu</td>
                                                  <td>Numer IMEI</td>
                                                  <td>PIN</td>
                                                  <td>PUK</td>
                                                  <td>Internet</td>
                                                  <td>Opis</td>
                                                  <td>Oddział</td>
                                                  <td>Pracownik</td>
                                                  <td>Akcja</td>
                                              </tr>
                                              @foreach($sim as $equipment)
                                                  @if($equipment->sim_type == 1)
                                                      <td>Abonament</td>
                                                  @else
                                                      <td>Prepaid</td>
                                                  @endif
                                                  <td>{{$equipment->sim_number_phone}}</td>
                                                  <td>{{$equipment->imei}}</td>
                                                  <td>{{$equipment->sim_pin}}</td>
                                                  <td>{{$equipment->sim_puk}}</td>
                                                  @if($equipment->sim_net == 1)
                                                      <td>Tak</td>
                                                  @else
                                                      <td>Nie</td>
                                                  @endif
                                                  <td>{{$equipment->description}}</td>
                                                  @if($equipment->department_info_id != 0)
                                                      <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                  @else
                                                      <td>Brak</td>
                                                  @endif
                                                  @if($equipment->user != null)
                                                      <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                  @else
                                                      <td>Brak</td>
                                                  @endif
                                                  <td>
                                                      <a href="edit_equipment/{{$equipment->id}}" class="btn btn-info">Edytuj</a>
                                                  </td>
                                                </tr>
                                              @endforeach
                                          </table>
                                          </div>
                                          <div class="table-responsive">
                                          <table class="table table-bordered" id="monitor" style="display: none">
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
                                              @foreach($screen as $equipment)
                                                  <td>{{$equipment->model}}</td>
                                                  <td>{{$equipment->serial_code}}</td>
                                                  @if($equipment->power_cable == 1)
                                                      <td>Tak</td>
                                                  @else
                                                      <td>Nie</td>
                                                  @endif
                                                  @if($equipment->signal_cable == 1)
                                                      <td>Tak</td>
                                                  @else
                                                      <td>Nie</td>
                                                  @endif
                                                  <td>{{$equipment->description}}</td>
                                                  @if($equipment->department_info_id != 0)
                                                      <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                  @else
                                                      <td>Brak</td>
                                                  @endif
                                                  @if($equipment->user != null)
                                                      <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                  @else
                                                      <td>Brak</td>
                                                  @endif
                                                  <td>
                                                      <a href="edit_equipment/{{$equipment->id}}" class="btn btn-info">Edytuj</a>
                                                  </td>
                                                </tr>
                                              @endforeach
                                          </table>
                                          </div>
                                          <div class="table-responsive">
                                          <table class="table table-bordered" id="printer" style="display: none">
                                              <tr>
                                                  <td>Model</td>
                                                  <td>Numer seryjny</td>
                                                  <td>Opis</td>
                                                  <td>Oddział</td>
                                                  <td>Pracownik</td>
                                                  <td>Akcja</td>
                                              </tr>
                                              @foreach($printer as $equipment)
                                                  <td>{{$equipment->model}}</td>
                                                  <td>{{$equipment->serial_code}}</td>
                                                  <td>{{$equipment->description}}</td>
                                                  @if($equipment->department_info_id != 0)
                                                      <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                  @else
                                                      <td>Brak</td>
                                                  @endif
                                                  @if($equipment->user != null)
                                                      <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                  @else
                                                      <td>Brak</td>
                                                  @endif
                                                  <td>
                                                      <a href="edit_equipment/{{$equipment->id}}" class="btn btn-info">Edytuj</a>
                                                  </td>
                                                </tr>
                                              @endforeach
                                          </table>
                                          </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')

<script>

      $(".equipment_data").on('click', function() {
          var id = this.name;

      });


      $('.menu_item').on('click', function(){
          var id = this.id;

          function deletePrevious() {
              $("#menu_laptop, #menu_tablet, #menu_phone, #menu_sim_card, #menu_monitor, #menu_printer").removeClass('active');
              $("#laptop, #tablet, #phone, #sim_card, #monitor, #printer").fadeOut(0);
          }

          if(id == "menu_laptop") {
              deletePrevious();
              $("#menu_laptop").addClass('active');
              $("#laptop").fadeIn(0);
          }

          if(id == "menu_tablet") {
              deletePrevious();
              $("#menu_tablet").addClass('active');
              $("#tablet").fadeIn(0);
          }

          if(id == "menu_phone") {
              deletePrevious();
              $("#menu_phone").addClass('active');
              $("#phone").fadeIn(0);
          }

          if(id == "menu_sim_card") {
              deletePrevious();
              $("#menu_sim_card").addClass('active');
              $("#sim_card").fadeIn(0);
          }

          if(id == "menu_monitor") {
              deletePrevious();
              $("#menu_monitor").addClass('active');
              $("#monitor").fadeIn(0);
          }

          if(id == "menu_printer") {
              deletePrevious();
              $("#menu_printer").addClass('active');
              $("#printer").fadeIn(0);
          }

      });





</script>
@endsection
