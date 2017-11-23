<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{url('/')}}">CRM Verona</a>
    </div>
    <!-- /.navbar-header -->

     {{--Logout info change password--}}
    {{--ZABLOKOWANE DLA DKJ--}}
    <ul class="nav navbar-top-links navbar-right">
        @if(Auth::user()->department_info->department_type->id == 6)
        <li class="dropdown">
            <a id="check_messages" class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-messages" style="width: 500px;">
                    <strong>Oddziały</strong>
                <li>
                    <div class="table-responsive">
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
            <ul class="dropdown-menu dropdown-messages" style="width: 700px; ">
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
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">{{Auth::user()->first_name.' '.Auth::user()->last_name}}
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="/password_change"><i class="fa fa-user fa-fw"></i>Zmiana hasła</a>
                </li>
                <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
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
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->




    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">

                <li>
                    <a href="{{url('/')}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>


                @foreach($groups as $group)
                            <li>
                                <a href="#"><i class="fa fa-files-o fa-fw"></i>{{$group->name}}<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">

                            @foreach($links as $link)
                                @if($group->id == $link->group_link_id)
                                    @if($link->link == 'show_all_notifications')
                                        <li>
                                              <a href="/show_all_notifications/1">{{$link->name}}</a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{url($link->link)}}">{{$link->name}}</a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            @endforeach
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>
