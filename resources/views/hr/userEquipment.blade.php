@if($user->equipments->count() > 0)

    <div class=" col-md-12 col-lg-12 ">
          <table class="table table-user-information">
              <tbody>
                  <tr>
                    <td style="width: 10px;"><b>Lp.</b></td>
                    <td><b>Data Wyd.</b></td>
                    <td><b>Sprzęt</b></td>
                    <td style="width: 10px;"></td>
                  </tr>
                  <?php $i = 1; ?>
                  @foreach($user->equipments as $equipment)
                  @if($equipment->deleted != 1)
                      <tr>
                        <td style="width: 10px;"><b>{{$i}}</b></td>
                        <td>{{$equipment->to_user}}</td>
                        <td>{{$equipment->equipment_type->name}}</td>
                        <td style="width: 10px;">
                            <button  type="button" id="{{$equipment->id}}" class="btn btn-info equipment_data" data-toggle="modal" data-target="#myModal"
                                data-equipment_id_database="{{$equipment->equipment_type->id}}"
                                data-equipment_type_id="{{$equipment->equipment_type->name}}"
                                data-laptop_processor="{{$equipment->laptop_processor}}"
                                data-laptop_ram="{{$equipment->laptop_ram}}"
                                data-laptop_hard_drive="{{$equipment->laptop_hard_drive}}"
                                data-phone_box="{{$equipment->phone_box}}"
                                data-tablet_modem="{{$equipment->tablet_modem}}"
                                data-sim_number_phone="{{$equipment->sim_number_phone}}"
                                data-sim_type="{{$equipment->sim_type}}"
                                data-sim_pin="{{$equipment->sim_pin}}"
                                data-sim_puk="{{$equipment->sim_puk}}"
                                data-sim_net="{{$equipment->sim_net}}"
                                data-model="{{$equipment->model}}"
                                data-serial_code="{{$equipment->serial_code}}"
                                data-description="{{$equipment->description}}"
                                data-power_cable="{{$equipment->power_cable}}"
                                data-signal_cable="{{$equipment->signal_cable}}"
                                data-status="{{$equipment->status}}"
                                data-id_user="{{$equipment->id_user}}"
                                data-to_user="{{$equipment->to_user}}"
                            >Dane techniczne</button>
                        </td>
                      </tr>
                      <?php $i++; ?>
                  @endif
                  @endforeach
              </tbody>
          </table>
    </div>
@else
    <div class="alert alert-info">Ten użytkownik nie posiada jeszcze własnego sprzętu!</div>
@endif

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Sprzęt firmowy</h4>
      </div>
      <div class="modal-body">
          <div class="table-responsive">
              <table class="table" id="modal_content">

              </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>
