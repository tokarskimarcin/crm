@if($link->link == 'view_users_table')
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
