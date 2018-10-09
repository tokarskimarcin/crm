<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="9" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">Raport Dzienny Szkoleń</font></td>
        <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <td colspan="11" style="border:1px solid #231f20;padding:3px;background:#231f20;color:#efd88f">Raport dla dnia: {{$start_date}}</td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Umówionych Etap - 1</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Obecnych Etap - 1</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nieobecnych Etap - 1</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Umówionych Etap - 2</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Obecnych Etap - 2</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nieobecnych Etap - 2</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Zatrudnieni / Obecni_Etap1</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Obecni_Etap2 / Obecni_Etap1</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Obecni_Etap1 / Umowieni_Etap1</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Zatrudnieni Kandydaci</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as  $item)
        @php
            $stat1 = $item->sum_choise_stageOne > 0 ? round(100 * $item->countHireUserFromFirstTrainingGroup / $item->sum_choise_stageOne, 2) : 0;
            $stat2 = $item->sum_choise_stageOne > 0 ? round(100 * $item->sum_choise_stageTwo /  $item->sum_choise_stageOne,2) : 0;
            $stat3 = $item->sum_choise_stageOne+$item->sum_absent_stageOne > 0 ? round(100 * $item->sum_choise_stageOne / ($item->sum_choise_stageOne+$item->sum_absent_stageOne),2) : 0;
        @endphp
        <tr>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->dep_name.' '.$item->dep_name_type}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #CCC">{{$item->sum_choise_stageOne+$item->sum_absent_stageOne}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #CCC">{{$item->sum_choise_stageOne}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #CCC">{{$item->sum_absent_stageOne}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sum_choise_stageTwo+$item->sum_absent_stageTwo}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->sum_choise_stageTwo}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;}}">{{$item->sum_absent_stageTwo}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #f5e79e">{{$stat1}}%</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #f5e79e">{{$stat2}}%</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #f5e79e">{{$stat3}}%</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #f5e79e">{{$item->countHireUserFromFirstTrainingGroup}}</td>
        </tr>
    @endforeach
    <tr>
        @php
            $stat_total_1 = $data->sum('sum_choise_stageOne') > 0 ? round(100 * $data->sum('countHireUserFromFirstTrainingGroup') / $data->sum('sum_choise_stageOne'), 2) : 0;
            $stat_total_2 = $data->sum('sum_choise_stageOne') > 0 ? round(100 * $data->sum('sum_choise_stageTwo') / $data->sum('sum_choise_stageOne'), 2) : 0;
            $stat_total_3 = ($data->sum('sum_choise_stageOne') + $data->sum('sum_absent_stageOne')) > 0 ? round(100 * $data->sum('sum_choise_stageOne') / ($data->sum('sum_choise_stageOne') + $data->sum('sum_absent_stageOne')), 2) : 0;
        @endphp
        <td colspan="1" style="border:1px solid #231f20;text-align:center;padding:3px"><b>TOTAL</b></td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #CCC">{{$data->sum('sum_choise_stageOne') + $data->sum('sum_absent_stageOne')}}</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #CCC">{{$data->sum('sum_choise_stageOne')}}</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #CCC">{{$data->sum('sum_absent_stageOne')}}</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$data->sum('sum_choise_stageTwo') + $data->sum('sum_absent_stageTwo')}}</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$data->sum('sum_choise_stageTwo')}}</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px;}}">{{$data->sum('sum_absent_stageTwo')}}</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$stat_total_1}}%</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$stat_total_2}}%</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$stat_total_3}}%</td>
        <td  style="border:1px solid #231f20;text-align:center;padding:3px;background-color: #f5e79e">{{$data->sum('countHireUserFromFirstTrainingGroup')}}</td>
    </tr>
    </tbody>
</table>
