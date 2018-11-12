@extends('layouts.main')

@section('style')
    <link rel="stylesheet" href="{{asset('css/AdminPanel/assignPrivilages/styles.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <div class="alert gray-nav">Przypisywanie Uprawnień</div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(Session::has('successMessage'))
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">{{Session::get('successMessage') }}</div>
                </div>
            </div>
            @php
                Session::forget('successMessage');
            @endphp
        @endif


        <div class="main-box">
            <div class="item-box">
                <form id="form1" action="{{URL::to('/assignPrivilages')}}" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Przepisz z roli na role</h3>
                        </div>
                    </div>

                    <div class="row">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="type" value="1">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role-first-role"> Z:</label>
                                <select class="form-control select2" name="role_first_role" id="role-first-role">
                                    <option value="0">Wybierz</option>
                                    @foreach ($allRoles as $id => $roleInfo)
                                        <option value="{{$roleInfo->id}}">{{$roleInfo->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role-second-role"> Na:</label>
                                <select class="form-control select2" name="role_second_role" id="role-second-role">
                                    <option value="0">Wybierz</option>
                                    @foreach ($allRoles as $id => $roleInfo)
                                        <option value="{{$roleInfo->id}}">{{$roleInfo->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="form-control btn btn-success" id="first-submit-button" value="Przepisz!">
                        </div>
                    </div>
                </form>
            </div>

            <div class="item-box">
                <form id="form2" action="{{URL::to('/assignPrivilages')}}" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Przepisz z roli na użytkownika</h3>
                        </div>
                    </div>

                    <div class="row">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="type" value="2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mixed-first-role"> Z:</label>
                                <select class="form-control select2" name="mixed_first_role" id="mixed-first-role">
                                    <option value="0">Wybierz</option>
                                    @foreach ($allRoles as $id => $roleInfo)
                                        <option value="{{$roleInfo->id}}">{{$roleInfo->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mixed-second-users"> Na:</label>
                                <select class="form-control select2" name="mixed_second_users" id="mixed-second-users">
                                    <option value="0">Wybierz</option>
                                    @foreach ($activeUsers as $id => $userInfo)
                                        <option value="{{$userInfo->id}}">{{$userInfo->first_name}} {{$userInfo->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="form-control btn btn-success" id="second-submit-button" value="Przepisz!">
                        </div>
                    </div>
                </form>
            </div>


            <div class="item-box">
                <form id="form3" action="{{URL::to('/assignPrivilages')}}" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Przepisz z użytkownika na użytkownika</h3>
                        </div>
                    </div>

                    <div class="row">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="type" value="3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="users-first-user"> Z:</label>
                                <select class="form-control select2" name="users_first_user" id="users-first-user">
                                    <option value="0">Wybierz</option>
                                    @foreach ($activeUsers as $id => $userInfo)
                                        <option value="{{$userInfo->id}}">{{$userInfo->first_name}} {{$userInfo->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="users-second-user"> Na:</label>
                                <select class="form-control select2" name="users_second_user" id="users-second-user">
                                    <option value="0">Wybierz</option>
                                    @foreach ($activeUsers as $id => $userInfo)
                                        <option value="{{$userInfo->id}}">{{$userInfo->first_name}} {{$userInfo->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="form-control btn btn-success" id="third-submit-button" value="Przepisz!">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {

        });
    </script>

    <script src="{{asset('js/AdminPanel/assignPrivilages/script.js')}}" defer></script>
@endsection
