{{--ZABLOKOWANE DLA Kierownik DKJ--}}
    @if($link->link == 'view_dkj_table_big')
    <li class="dropdown"  style="color: #000 !important">
        <a id="check_messages_dkj" class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-envelope fa-fw"></i><i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-messages" style="width: 150vw; max-height: 350px;max-width: 700px; margin-right: -100px">
                <strong>Oddziały</strong>
            <li>
                <div class="table-responsive" style="max-height: 300px">
                  <table class="table table-bordered" style="margin-bottom:0px; color: #000; background-color: #fff" >
                    <thead>
                        <tr>
                            <th style="width: 10%">Lp.</th>
                            <th>Oddział</th>
                            <th>Status</th>
                            <th style="width: 20%">Janki</th>
                            <th style="width: 10%">Odrzuconych</th>
                        </tr>
                    </thead>
                    <tbody>
                      @php $i = 1;  @endphp
                      @foreach($departments_for_dkj as $department)
                          @if($department->type == 'Badania/Wysyłka')
                              <tr id="{{$department->id}}dkjstatus">
                                  <td>{{$i}}</td>
                                  <td>{{$department->departments->name . ' ' . $department->department_type->name.' Badania '}}</td>
                                  <td name="status" class="alert alert-danger">Nieodsłuchany</td>
                                  <td name="count_yanek">0</td>
                                  <td name="undone">0</td>
                              </tr>
                              @php $i++;  @endphp
                              <tr id="{{$department->id*(-1)}}dkjstatus">
                                  <td>{{$i}}</td>
                                  <td>{{$department->departments->name . ' ' . $department->department_type->name.' Wysyłka '}}</td>
                                  <td name="status" class="alert alert-danger">Nieodsłuchany</td>
                                  <td name="count_yanek">0</td>
                                  <td name="undone">0</td>
                              </tr>
                          @elseif($department->type == 'Badania' || $department->type == 'Wysyłka')
                              <tr id="{{$department->id}}dkjstatus">
                                  <td>{{$i}}</td>
                                  <td>{{$department->departments->name . ' ' . $department->department_type->name.' '.$department->type}}</td>
                                  <td name="status" class="alert alert-danger">Nieodsłuchany</td>
                                  <td name="count_yanek">0</td>
                                  <td name="undone">0</td>
                              </tr>
                          @endif
                          @php $i++;  @endphp
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </li>
        </ul>
    </li>
    @endif
