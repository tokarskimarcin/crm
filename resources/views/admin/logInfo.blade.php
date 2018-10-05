{{--/*--}}
{{--*@category: Panel Administratorski,--}}
{{--*@info: This view allows admin to look at logs (DB table: "log_info"),--}}
{{--*@controller: AdminController,--}}
{{--*@methods: logInfoGet--}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Informacje o logach</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">Od</span>
                                <input id="from_date" type="date" class="form-control" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon2">Do</span>
                                <input id="to_date" type="date" class="form-control" aria-describedby="basic-addon2">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon3">Grupa linków</span>
                                <select id="link_group" class="form-control" aria-describedby="basic-addon3" ></select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon4">Typ akcji</span>
                                <select id="log_action_type" class="form-control" aria-describedby="basic-addon4" ></select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 1em">
                        <div class="col-md-12">
                            <table id="datatable" class="table table-striped cell-border hover order-column row-border"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>Kto</th>
                                    <th>Link</th>
                                    <th>Typ akcji</th>
                                    <th>Data</th>
                                    <th>Komentarz</th>
                                    <th>Imię</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);

        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        var firstDayOfThisMonth = now.getFullYear()+"-"+(month)+"-01";

        let fromDate = $('#from_date');
        let toDate = $('#to_date');
        fromDate.val(today);
        toDate.val(today);

        let linkGroups = $('#link_group');
        let linkGroupsArray = JSON.parse(<?php echo json_encode($linkGroups) ?>);
        option = document.createElement('option');
        option.value = 0;
        option.text = 'Wszystkie';
        linkGroups.append($(option));
        linkGroupsArray.forEach(function (item, index) {
            option = document.createElement('option');
            option.value = item.id;
            option.text = item.name;
            linkGroups.append($(option));
        });

        let logActionType = $('#log_action_type');
        let logActionTypeArray = JSON.parse(<?php echo json_encode($logActionType) ?>);
        option = document.createElement('option');
        option.value = 0;
        option.text = 'Wszystkie';
        logActionType.append($(option));
        logActionTypeArray.forEach(function (item, index) {
            option = document.createElement('option');
            option.value = item.id;
            option.text = item.name;
            logActionType.append($(option));
        });

        let table = $('#datatable').DataTable({
            autoWidth: true,
            scrollY: '45vh',
            processing: true,
            serverSide: true,
            order: [[3, "desc"]],
            ajax: {
                url: `{{ route('api.datatableLogInfo') }}`,
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function (d) {
                    d.fromDate = fromDate.val();
                    d.toDate = toDate.val();
                    d.action_type_id = $('#log_action_type').val();
                    d.group_link_id = $('#link_group').val();
                }
            },
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },
            fnDrawCallback: function(oSettings){
                $('.comment').click((e)=>{
                    swal({
                        text: $(e.target).data('text')
                    });
                })
            },
            columns: [
                {data: function(data, type, dataToSet){
                    return data.first_name+' '+data.last_name;
                    }, name: 'last_name'},
                {data: 'link'},
                {data: 'action_name', visible: false},
                {data: 'updated_at'},
                {data: function (data, type, dataToSet) {
                    if(data.comment === null){
                        return '';
                    }
                    if(data.comment.length > 21) {
                        return data.comment.substring(0, 30) + '<button class="btn btn-default comment" style="float: right" type="button" data-text="'+
                            data.comment+'">' +
                            '<span class="glyphicon glyphicon-option-horizontal"></span></button>';
                    }else
                        return data.comment;
                    },name:'comment', bSortable: false},
                {data: 'first_name', visible:false}
            ],
            columnDefs: [
                {width: '20%}', targets: [0, 1, 2, 3, 4]}
            ]
        });

        fromDate.change(()=>{
            if(new Date(fromDate.val())>new Date(toDate.val()))
                toDate.val(fromDate.val());
            table.ajax.reload();
        });

        toDate.change(()=>{
            if(new Date(fromDate.val())>new Date(toDate.val()))
                fromDate.val(toDate.val());
            table.ajax.reload();
        });

        $('#log_action_type').change(()=>{
            table.ajax.reload();
        })

        $('#link_group').change(()=>{
            table.ajax.reload();
        })
    </script>
@endsection
