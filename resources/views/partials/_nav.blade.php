<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{url('/')}}">TeamBox</a>

    </div>
    <!-- /.navbar-header -->

     {{--Logout info change password--}}

    <ul class="nav navbar-top-links navbar-right">
@if(Auth::user()->department_info->blocked == 0)
      {{--ZABLOKOWANE DLA IT--}}
        @foreach($links as $link)
            @if($link->link == 'view_notification_table')
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="it_support">Zgłoszenia
                    <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul id="babum" class="dropdown-menu dropdown-alerts" style="width: 80vh; overflow-y:scroll; max-height: 500px; margin-right: -200px">

                </ul>
            </li>
            @endif
      {{--ZABLOKOWANE DLA DKJ--}}
            @if($link->link == 'view_dkj_table_small')
            <li class="dropdown">
                <a id="check_messages" class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-messages" style="width: 80vh; max-height: 550px; margin-right: -200px">
                        <strong>Oddziały</strong>
                    <li>
                        <div class="table-responsive"  style="max-height: 500px">
                          <table class="table table-bordered" style="margin-bottom:0px">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Lp.</th>
                                    <th>Oddział</th>
                                    <th>Status</th>
                                    <th style="width: 20%">Janki</th>
                                </tr>
                            </thead>
                            <tbody>
                              @php($i = 1)
                              @foreach($departments_for_dkj as $department)
                                  @if($department->type == 'Badania/Wysyłka')
                                      <tr id="{{$department->id}}dkjstatus">
                                          <td>{{$i}}</td>
                                          <td>{{$department->departments->name . ' ' . $department->department_type->name.' Badania '}}</td>
                                          <td name="status" class="alert alert-danger">Nieodsłuchany</td>
                                          <td name="count_yanek">0</td>
                                      </tr>
                                      @php($i++)
                                      <tr id="{{$department->id*(-1)}}dkjstatus">
                                          <td>{{$i}}</td>
                                          <td>{{$department->departments->name . ' ' . $department->department_type->name.' Wysyłka '}}</td>
                                          <td name="status" class="alert alert-danger">Nieodsłuchany</td>
                                          <td name="count_yanek">0</td>
                                      </tr>
                                      @elseif($department->type == 'Badania' || $department->type == 'Wysyłka')
                                      <tr id="{{$department->id}}dkjstatus">
                                          <td>{{$i}}</td>
                                          <td>{{$department->departments->name . ' ' . $department->department_type->name.' '.$department->type}}</td>
                                          <td name="status" class="alert alert-danger">Nieodsłuchany</td>
                                          <td name="count_yanek">0</td>
                                      </tr>
                                  @endif
                                  @php($i++)
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a id="check_users" class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-messages" style="width: 80vh; margin-right: -200px">
                    <strong>Konsultanci</strong>
                  <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-bordered" id="consultantTable">
                      <thead>
                          <tr>
                              <th style="width: 10%">Lp.</th>
                              <th>Imie i nazwisko</th>
                              <th>Ilość odsłuchanych</th>
                              <th style="width: 20%">Janki</th>
                          </tr>
                      </thead>
                      <tbody id="tableContent">
                      </tbody>
                    </table>
                  </div>
                </ul>
            </li>
            @endif
        {{--ZABLOKOWANE DLA Kierownik DKJ--}}
            @if($link->link == 'view_dkj_table_big')
            <li class="dropdown">
                <a id="check_messages_dkj" class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-envelope fa-fw"></i><i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-messages" style="width: 150vh; max-height: 350px; margin-right: -200px">
                        <strong>Oddziały</strong>
                    <li>
                        <div class="table-responsive" style="max-height: 300px">
                          <table class="table table-bordered" style="margin-bottom:0px">
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
                              @php($i = 1)
                              @foreach($departments_for_dkj as $department)
                                  @if($department->type == 'Badania/Wysyłka')
                                      <tr id="{{$department->id}}dkjstatus">
                                          <td>{{$i}}</td>
                                          <td>{{$department->departments->name . ' ' . $department->department_type->name.' Badania '}}</td>
                                          <td name="status" class="alert alert-danger">Nieodsłuchany</td>
                                          <td name="count_yanek">0</td>
                                          <td name="undone">0</td>
                                      </tr>
                                      @php($i++)
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
                                  @php($i++)
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                    </li>
                </ul>
            </li>
            @endif
        @endforeach


        <!-- start new section-->

        <!-- Endo of new section -->


        @if($multiple_departments->count() != 0)
        <li>
            <label for="select_town">Wybierz oddział</label>
        </li>
        <li>
            <select id="change_department" class="form-control">
              @foreach($multiple_departments as $department)
                  <option @if(Auth::user()->department_info_id == $department->department_info_id) selected @endif value="{{$department->department_info_id}}">{{$department->department_info->departments->name . ' ' . $department->department_info->department_type->name}}</option>
              @endforeach
            </select>
        </li>
        @endif
@endif
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">{{Auth::user()->first_name.' '.Auth::user()->last_name}}
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{URL::to('/password_change')}}"><i class="fa fa-user fa-fw"></i>Zmiana hasła</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a  href="{{ route('logout') }}" onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out fa-fw"></i>
                        Wyglouj</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>
    </ul>

    <div class="navbar-default sidebar pre-scrollable" role="navigation" style="min-height: 93vh"  id="my_left_menu">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="{{url('/')}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                @if(Auth::user()->department_info->blocked == 0)
                  @foreach($groups->where('id','!=',8) as $group)
                              <li>
                                  <a href="#"><i class="fa fa-files-o fa-fw"></i>{{$group->name}}<span class="fa arrow"></span></a>
                                  <ul class="nav nav-second-level">

                              @foreach($links as $link)
                                  @if($group->id == $link->group_link_id)
                                      @if($link->link == 'show_all_notifications')
                                          <li>
                                                <a href="{{URL::to('/show_all_notifications/1')}}">{{$link->name}}</a>
                                          </li>
                                      @else
                                          <li>
                                              <a href="{{url($link->link)}}">{{$link->name}}</a>
                                          </li>
                                      @endif
                                  @endif
                              @endforeach
                                  </ul>
                              </li>
                              @endforeach
                @endif

            </ul>
        </div>
    </div>

    {{--@if($link->link == 'janky_notification' || 1 == 1)--}}
        {{--<div id="janky_notyfication">--}}
            {{--<div class="simple-marquee-container" style="margin-top: 2px">--}}
                {{--<div class="marquee">--}}
                    {{--<ul class="marquee-content-items">--}}
                        {{--<li style="font-family: inherit;"> Masz Jednego Niezweryfikowanego janka</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--@endif--}}
</nav>
