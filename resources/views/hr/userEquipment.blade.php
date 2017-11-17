@if($user->equipments->count() > 0)


    <div class=" col-md-10 col-lg-10 ">
          <table class="table table-user-information">
              <tbody>
                  <b style="font-size: 20px; font-family: sans-serif;">Posiadany Sprzęt</b>
                  <tr>
                    <td style="width: 10px;"><b>Lp.</b></td>
                    <td><b>Data Wyd.</b></td>
                    <td><b>Sprzęt</b></td>
                    <td style="width: 10px;"></td>
                  </tr>
                  <?php $i = 1; ?>
                  @foreach($user->equipments as $equipment)
                      <tr>
                        <td style="width: 10px;"><b>{{$i}}</b></td>
                        <td>{{$equipment->created_at}}</td>
                        <td>{{$equipment->equipment_type->name}}</td>
                        <td style="width: 10px;">
                            <button type="button" name="{{$equipment->id}}" class="btn btn-info" data-toggle="modal" data-target="#myModal">Dane techniczne</button>
                        </td>
                      </tr>
                      <?php $i++; ?>
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
              <table class="table">
                  <tr>
                    <td style="width: 100px;"><b>Data Wydania</b></td>
                    <td style="width: 10px;">2017-03-04</td>
                  </tr>
              </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>
