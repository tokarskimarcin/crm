<table class="table table-user-information">
  <tbody>
    @if(($user->penalty_bonuses->where('status', '!=', 0)->count()) > 0)
          <tr>
            <td><b>Data</b></td>
            <td><b>Kara/Premia</b></td>
            <td><b>Dodał</b></td>
            <td><b>Powód</b></td>
            <td></td>
          </tr>

    @else
        <div class="alert alert-info">Ten użytkownik nie ma jeszcze kar/premii!</div>
    @endif
    @php $limit_event_date = date("Y-m", strtotime("-2 months"));  @endphp
    @foreach($user->penalty_bonuses->where('status', '!=', 0)->where('event_date', '>=', $limit_event_date.'-01') as $penalty)
        <tr name={{$penalty->id}}>
          <td nowrap="nowrap">{{$penalty->event_date}}</td>
          @if($penalty->type == 2)
              <td nowrap="nowrap"><span style="background-color: #70ff5c; padding: 4px 10px;border-radius: 5px;border:1px solid #33ff36; color:#4b5c44;">Premia: {{$penalty->amount}} zł</span></td>
          @else
              <td nowrap="nowrap"><span style="background-color: #ff7b7b; padding: 4px 10px;border-radius: 5px;border:1px solid #ff6a6a; color:#7f2222;">Kara: {{$penalty->amount}} zł</span></td>
          @endif
          <td nowrap="nowrap"><span style="background-color: #d9edf7; padding: 4px 10px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">{{$penalty->manager->first_name . ' ' . $penalty->manager->last_name}}</span></td>
          <td>{{$penalty->comment}}</td>
          <td><button class="btn btn-danger btn-sm action delete" id="{{$penalty->id}}">Usuń</button></td>
        </tr>
    @endforeach
    @if (Session::has('message'))
       <div class="alert alert-success">{{ Session::get('message') }}</div>
    @endif

    <tr>
        <form method="POST" action="{{URL::to('/view_penalty_bonus_edit/')}}" id="pb">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="user_id" value="{{$user->id}}">
            <td colspan="1">
                <select class="form-control" name="penalty_type">
                    <option>Wybierz</option>
                    <option value="1">Kara</option>
                    <option value="2">Premia</option>
                </select>
            </td>
            <td>
              <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
              <input class="form-control" name="date_penalty" type="text" value="{{date("Y-m-d")}}" readonly>
              <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span></div>
            </td>
            <td><input type="number" placeholder="0" name="cost" class="form-control"></td>
            <td colspan="2"><input type="text" placeholder="Powód" name="reason" class="form-control"></td>
            <td><input value="Dodaj" type="submit" id="addpbsubmit" class="btn btn-info"></td>
        </form>
    </tr>
  </tbody>
</table>
<div class="alert alert-danger" style="display: none" id="alert_reason">
    Podaj powód!
</div>
<div class="alert alert-danger" style="display: none" id="alert_value">
    Podaj kwotę kary/premii!
</div>
<div class="alert alert-danger" style="display: none" id="alert_value_plus">
    Kara/premia musi być dodatnia!
</div>
<div class="alert alert-danger" style="display: none" id="alert_select">
    Wybierz rodzaj kary/premii!
</div>
