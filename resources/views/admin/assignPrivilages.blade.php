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

    <div class="main-box">
        <div class="item-box">
          <form action="{{URL::to('/assignPrivilages')}}" method="post">
            <div class="row">
                <div class="col-md-12">
                    <h3>Przepisz z roli na role</h3>
                </div>
            </div>

            <div class="row">
              <input type="hidden" name="type" value="4">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="role-first-role"> Z:</label>
                      <select class="form-control select2" name="role-first-role" id="role-first-role">
                          <option value="0">Wybierz</option>
                          @foreach ($allRoles as $id => $roleInfo)
                            <option value="{{$id}}">{{$roleInfo->name}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                      <label for="role-second-role"> Na:</label>
                      <select class="form-control select2" name="role-second-role" id="role-second-role">
                          <option value="0">Wybierz</option>
                          @foreach ($allRoles as $id => $roleInfo)
                            <option value="{{$id}}">{{$roleInfo->name}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                  <input type="submit" class="form-control" name="first-submit" value="Przepisz!">
              </div>
            </div>
          </form>
        </div>

        <div class="item-box">
          <form class="" action="index.html" method="post">
            <div class="row">
                <div class="col-md-12">
                    <h3>Przepisz z roli na użytkownika</h3>
                </div>
            </div>

            <div class="row">
                <input type="hidden" name="type" value="2">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="mixed-first-role"> Z:</label>
                      <select class="form-control select2" name="mixed-first-role" id="mixed-first-role">
                          <option value="0">Wybierz</option>
                          @foreach ($allRoles as $id => $roleInfo)
                            <option value="{{$id}}">{{$roleInfo->name}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                      <label for="mixed-second-users"> Na:</label>
                      <select class="form-control select2" name="mixed-second-users" id="mixed-second-users">
                          <option value="0">Wybierz</option>
                          @foreach ($activeUsers as $id => $userInfo)
                            <option value="{{$id}}">{{$userInfo->first_name}} {{$userInfo->last_name}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                  <input type="submit" class="form-control" name="first-submit" value="Przepisz!">
              </div>
            </div>
          </form>
        </div>


        <div class="item-box">
          <form class="" action="index.html" method="post">
            <div class="row">
                <div class="col-md-12">
                    <h3>Przepisz z użytkownika na użytkownika</h3>
                </div>
            </div>

            <div class="row">
                <input type="hidden" name="type" value="3">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="users-first-user"> Z:</label>
                      <select class="form-control select2" name="users-first-user" id="users-first-user">
                          <option value="0">Wybierz</option>
                          @foreach ($activeUsers as $id => $userInfo)
                            <option value="{{$id}}">{{$userInfo->first_name}} {{$userInfo->last_name}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                      <label for="users-second-user"> Na:</label>
                      <select class="form-control select2" name="users-second-user" id="users-second-user">
                          <option value="0">Wybierz</option>
                          @foreach ($activeUsers as $id => $userInfo)
                            <option value="{{$id}}">{{$userInfo->first_name}} {{$userInfo->last_name}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                  <input type="submit" class="form-control" name="first-submit" value="Przepisz!">
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
