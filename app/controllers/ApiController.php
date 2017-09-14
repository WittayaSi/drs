<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\App;

use Illuminate\Pagination\Paginator;
//use Maatwebsite\Excel\Facades\Excel;

class ApiController
{
    public function getResultsOnChangedEventSection()
    {
        $data['server'] = $_SERVER;
        $req = json_decode(file_get_contents("php://input"), true);
        //$arr = explode(',', $req);
        //die(var_dump($req));
        $full_sql = "";
        $date1 = $req["date1"];
        $date2 = $req["date2"];
        $province_id = $req['p'];
        $district_id = $req['d'];
        $sdistrict_id = $req['s'];
        $s_check_status = $req['c'];
        $c_blood_status = $req['cb'];
        $other = $req['other'];
        $page = $req['page'];

        $full_sql = "SELECT * FROM data_pos_racd_v2 AS d WHERE (d.blood_draw_date>='{$date1}' AND d.blood_draw_date<='{$date2}')";
        $data['provinces'] = App::get('database')->selectWhere('TBL_MS_PROVINCE','PROVINCE_STATUS', 1);

        //$data['provinces'] = Province::where('PROVINCE_STATUS', "1")->get();

        if($sdistrict_id != ""){
            $full_sql = $full_sql." AND d.p_site_subdistrict_id = '".$sdistrict_id."'";
        }
        if($district_id != ""){
            $full_sql = $full_sql." AND d.p_site_district_id = '".$district_id."'";
            $data['selectedSubdistricts'] = App::get('database')->selectWhere('TBL_MS_SUBDISTRICT', 'DISTRICT_ID', $district_id);
        }
        if(!empty($province_id) && $province_id[0] != ""){
            $district_all_arr = [];
            $province_sql = " AND ( ";
            foreach ($province_id as $key=>$value) {
                if($key == 0){
                    $province_sql = $province_sql." d.p_site_province_id = '".$value."'" ;
                }else{
                    $province_sql = $province_sql." OR d.p_site_province_id = '".$value."'" ;
                }
                $district_arr = App::get('database')->selectWhere('TBL_MS_DISTRICT','PROVINCE_ID',$value);
                foreach ($district_arr as $key1=>$value1) {
                    if($key1 != ""){
                        $district_all_arr[] = $value1;
                    }
                }
            }
            $province_sql = $province_sql.") ";

            $full_sql = $full_sql . $province_sql;

            $data['selectedDistricts'] = $district_all_arr;
        }

        if($other == 'true'){
            $full_sql = $full_sql." AND (result_code_detail_1 <> 'F' AND result_code_detail_1 <> 'V' AND result_code_detail_1 <> 'Mix')";
        }else{
            $sql_check_blood_result_box = App::get('database')->selectWhere('check_box_sql','id',$c_blood_status);
            //die(var_dump(json_decode(json_encode($sql_check_blood_result_box), true)[0]['sql_content']));
            $full_sql = $full_sql." ".json_array($sql_check_blood_result_box)[0]['sql_content'];
        }

        $sql_check_box = App::get('database')->selectWhere('check_box_sql', 'id', $s_check_status);

        $sql_order = "ORDER BY d.blood_draw_date DESC";

        $full_sql = $full_sql." ".json_array($sql_check_box)[0]['sql_content']." ".$sql_order;
        $data['sql'] = $full_sql;

        $total = count(App::get('database')->selectSql($full_sql));
        $data['total'] = $total;
        $query = json_array(App::get('database')->selectSql($full_sql));

        $perPage = 20;
        $currentPage = $page;
        $overData = $total % $perPage;
        $lastPage = $total > 0 ? ($total - $overData) / $perPage : 0 ;
        if($overData > 0 && $total > 0){
            $data['lastPage'] = $lastPage + 1;
        }else{
            $data['lastPage'] = $lastPage;
        }
        $pagedData = array_slice($query, (($currentPage-1) * $perPage), $perPage);
        $data['pagination'] =  new Paginator($pagedData, $perPage, $currentPage);

        die(json_encode($data));
    }

    public function exportExcelFile()
    {
        $full_sql = "";
        //dd($req);
        $date1 = $_GET['date1'];
        $date2 = $_GET['date2'];
        $province_id = $_GET['p'];
        $district_id = $_GET['d'];
        $sdistrict_id = $_GET['s'];
        $s_check_status = $_GET['c'];
        $c_blood_status = $_GET['cb'];
        $other = $_GET['other'];
        $file_type = $_GET['t'];

        // $year1 = date('Y', strtotime($req->date1));
        // $month1 = date('n', strtotime($req->date1));

        //dir(var_dump($_GET));

        $full_sql = "SELECT * FROM data_pos_racd_v2 AS d WHERE (d.blood_draw_date>='".$date1."' AND d.blood_draw_date<='".$date2."')";

        //$data['provinces'] = Province::where('PROVINCE_STATUS', "1")->get();

        if($sdistrict_id != ""){
            $full_sql = $full_sql." AND d.p_site_subdistrict_id = '".$sdistrict_id."'";
        }
        if($district_id != ""){
            $full_sql = $full_sql." AND d.p_site_district_id = '".$district_id."'";
            $data['selectedSubdistricts'] = App::get('database')->selectWhere('TBL_MS_SUBDISTRICT', 'DISTRICT_ID', $district_id);
        }
        if($province_id != ""){
            $p_array = explode(",",$province_id);
            $district_all_arr = [];
            $province_sql = " AND ( ";
            foreach ($p_array as $key=>$value) {
                if($key == 0){
                    $province_sql = $province_sql." d.p_site_province_id = '".$value."'" ;
                }else{
                    $province_sql = $province_sql." OR d.p_site_province_id = '".$value."'" ;
                }
                $district_arr = App::get('database')->selectWhere('TBL_MS_DISTRICT','PROVINCE_ID',$value);
                foreach ($district_arr as $key1=>$value1) {
                    if($key1 != ""){
                        $district_all_arr[] = $value1;
                    }
                }
            }
            $province_sql = $province_sql.") ";

            $full_sql = $full_sql . $province_sql;

            $data['selectedDistricts'] = $district_all_arr;
        }

        if($other == 'true'){
            $full_sql = $full_sql." AND (result_code_detail_1 <> 'F' AND result_code_detail_1 <> 'V' AND result_code_detail_1 <> 'Mix')";
        }else{
            $sql_check_blood_result_box = App::get('database')->selectWhere('check_box_sql','id',$c_blood_status);
            $full_sql = $full_sql." ".json_array($sql_check_blood_result_box)[0]['sql_content'];
        }

        $sql_check_box = App::get('database')->selectWhere('check_box_sql', 'id', $s_check_status);

        $sql_order = "ORDER BY d.blood_draw_date DESC";

        $full_sql = $full_sql." ".json_array($sql_check_box)[0]['sql_content']." ".$sql_order;

        $data['results'] = json_array(App::get('database')->selectSql($full_sql));
        $data['file_type'] = $file_type;

        return view('drs/export-excel', $data);

//        foreach($data as $object)
//        {
//            $data_arr[] =  (array) $object;
//        }
//        return Excel::create('report_book_'.$date1, function($excel) use ($data_arr) {
//            $excel->sheet('sheet1', function($sheet) use ($data_arr)
//            {
//                $sheet->fromArray($data_arr);
//                //$sheet->setWrapText(true);
//            });
//        })->download($file_type);
    }
}
