<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 18.09.18
 * Time: 09:47
 */
?>
<html>
<style>
    .handPointer:hover{
        cursor: pointer;
    }
    .myPanels .green:hover{

        background-color: #449d44 !important;
        border-color: #398439 !important;
    }
    .myPanels .green{
        background-color: #5cb85c !important;
        border-color: #4cae4c !important;
    }
    .myPanels{
        -webkit-box-shadow: 0px 0px 10px 1px rgba(0,0,0,0.5);
        -moz-box-shadow: 0px 0px 10px 1px rgba(0,0,0,0.5);
        box-shadow: 0px 0px 10px 1px rgba(0,0,0,0.5);
    }
</style>
<div id="content">
    @foreach($employeesOfTheWeek as $employeeOfTheWeek)
        @if(strtotime($employeeOfTheWeek->last_day_week)<strtotime('today'))
            <div class="panel panel-default myPanels">
            @if($employeeOfTheWeek->accepted == 1)
                    <div class="panel-heading handPointer" data-toggle="collapse" data-target="#body_{{$employeeOfTheWeek->id}}" aria-expanded="false">
            @else
                    <div class="panel-heading handPointer green" data-toggle="collapse" data-target="#body_{{$employeeOfTheWeek->id}}" aria-expanded="false">
            @endif
                        <span class="caret"></span> Tydzień: {{date('Y.m.d',strtotime($employeeOfTheWeek->first_day_week))}} - {{date('Y.m.d',strtotime($employeeOfTheWeek->last_day_week))}}
                </div>
                <div id="body_{{$employeeOfTheWeek->id}}" class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table id="datatable_{{$employeeOfTheWeek->id}}" class="table-bordered table-striped datatable" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>Lp.</th>
                                    <th>Imię i nazwisko</th>
                                    <th>% zielonych (lb. pokazów)</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($employeesOfTheWeekRankings->where('employee_of_the_week_id',$employeeOfTheWeek->id) as $employeeOfTheWeekRanking)
                                           <tr id="{{$employeeOfTheWeekRanking->user_id}}">
                                               <td>{{$employeeOfTheWeekRanking->ranking_position}}</td>
                                               <td>{{$employeeOfTheWeekRanking->user->first_name}} {{$employeeOfTheWeekRanking->user->last_name}}</td>
                                               <td>{{$employeeOfTheWeekRanking->criterion}}</td>
                                           </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($employeeOfTheWeek->accepted == 0 )
                            <div class="col-md-6">
                                <button class="btn btn-block btn-success">Akceptuj premie</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
<script>
    VARIABLES.SUBVIEW = {
        myPanels: $('.myPanels'),
        DATA_TABLES: {
            tables: $('.datatable'),
            dataTables: []
        }
    };

    FUNCTIONS.SUBVIEW ={
        EVENT_HANDLERS: {
          callEvents: function () {
              (function panelHeadingClickHandler() {
                  VARIABLES.SUBVIEW.myPanels.find('.panel-body').on('shown.bs.collapse',function (e) {
                      let employeeOfTheWeekId = ($(e.target).attr('id')).split('_')[1];
                      VARIABLES.SUBVIEW.DATA_TABLES.dataTables[employeeOfTheWeekId].columns.adjust().draw();
                  });
              })();
          }
        },
        call: function () {
            $.each(VARIABLES.SUBVIEW.DATA_TABLES.tables,function (index, table) {
                let employeeOfTheWeekId = $(table).attr('id').split('_')[1];
                VARIABLES.SUBVIEW.DATA_TABLES.dataTables[employeeOfTheWeekId] = $(table).DataTable({
                    scrollY: '20vh',
                    paging: false,
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                    }
                });
            });
            let array = [];
            $.each(VARIABLES.SUBVIEW.DATA_TABLES.tables,function (index, table) {
                let employeeOfTheWeekId = $(table).attr('id').split('_')[1];
                array.push(VARIABLES.SUBVIEW.DATA_TABLES.dataTables[employeeOfTheWeekId]);
            });
            resizeDatatablesOnMenuToggle(array);
            VARIABLES.SUBVIEW.myPanels.find('.panel-body').addClass('collapse');
        }
    };
    FUNCTIONS.SUBVIEW.call();
    FUNCTIONS.SUBVIEW.EVENT_HANDLERS.callEvents();
</script>
</html>
