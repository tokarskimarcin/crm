<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{ url('/') }}">CRM Verona</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">


            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="HomePage">
                <a class="nav-link"  href="{{ url('/') }}">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="nav-link-text">Strona Domowa</span>
                </a>
            </li>


            @foreach($groups as $group)
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapse{{$group->name}}" data-parent="#exampleAccordion">
                    <i class="fa fa-fw fa-wrench"></i>
                    <span class="nav-link-text">{{$group->name}}</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapse{{$group->name}}">
                @foreach($links as $link)
                    @if($group->id == $link->group_link_id)
                            <li>
                                <a href="{{url($link->link)}}">{{$link->name}}</a>
                            </li>
                    @endif
                @endforeach
                </ul>
            </li>
            @endforeach

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
                <a class="nav-link" href="#">
                    <i class="fa fa-fw fa-link"></i>
                    <span class="nav-link-text">Link</span>
                </a>
            </li>




        </ul>
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="sidenavToggler">
                    <i class="fa fa-fw fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">

            {{--<li class="nav-item dropdown">--}}
                {{--<a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{--<i class="fa fa-fw fa-bell"></i>--}}
                    {{--<span class="d-lg-none">Alerts--}}
              {{--<span class="badge badge-pill badge-warning">6 New</span>--}}
            {{--</span>--}}
                    {{--<span class="indicator text-warning d-none d-lg-block">--}}
              {{--<i class="fa fa-fw fa-circle"></i>--}}
            {{--</span>--}}
                {{--</a>--}}
                {{--<div class="dropdown-menu" aria-labelledby="alertsDropdown">--}}
                    {{--<h6 class="dropdown-header">New Alerts:</h6>--}}
                    {{--<div class="dropdown-divider"></div>--}}
                    {{--<a class="dropdown-item" href="#">--}}
              {{--<span class="text-success">--}}
                {{--<strong>--}}
                  {{--<i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>--}}
              {{--</span>--}}
                        {{--<span class="small float-right text-muted">11:21 AM</span>--}}
                        {{--<div class="dropdown-message small">This is an automated server response message. All systems are online.</div>--}}
                    {{--</a>--}}
                    {{--<div class="dropdown-divider"></div>--}}
                    {{--<a class="dropdown-item" href="#">--}}
              {{--<span class="text-danger">--}}
                {{--<strong>--}}
                  {{--<i class="fa fa-long-arrow-down fa-fw"></i>Status Update</strong>--}}
              {{--</span>--}}
                        {{--<span class="small float-right text-muted">11:21 AM</span>--}}
                        {{--<div class="dropdown-message small">This is an automated server response message. All systems are online.</div>--}}
                    {{--</a>--}}
                    {{--<div class="dropdown-divider"></div>--}}
                    {{--<a class="dropdown-item" href="#">--}}
              {{--<span class="text-success">--}}
                {{--<strong>--}}
                  {{--<i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>--}}
              {{--</span>--}}
                        {{--<span class="small float-right text-muted">11:21 AM</span>--}}
                        {{--<div class="dropdown-message small">This is an automated server response message. All systems are online.</div>--}}
                    {{--</a>--}}
                    {{--<div class="dropdown-divider"></div>--}}
                    {{--<a class="dropdown-item small" href="#">View all alerts</a>--}}
                {{--</div>--}}
            {{--</li>--}}

            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-fw fa-sign-out"></i>Logout</a>
            </li>
        </ul>
    </div>
</nav>