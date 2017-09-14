<?php
    $filename = "export_data_" . date('Ymd') .'.'. $file_type;
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel");
?>
<!DOCTYPE html>
<html
lang="en"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
</head>
<body>
    <table class="table table-bordered table-hover table-responsive result-table">
        <thead>
            <tr class="alert-info">
                <th style="text-align: center">treatment place</th>
                <th style="text-align: center">blood draw date</th>
                <th style="text-align: center" nowrap>ชื่อ - สกุล</th>
                <th style="text-align: center">ประเภทผู้ป่วย</th>
                <th style="text-align: center">เพศ</th>
                <th style="text-align: center">อายุ (ปี)</th>
                <th style="text-align: center">อาชีพ</th>
                <th style="text-align: center">หมู่บ้าน</th>
                <th style="text-align: center">หมู่</th>
                <th style="text-align: center">ตำบล</th>
                <th style="text-align: center">อำเภอ</th>
                <th style="text-align: center">จังหวัด</th>
                <th style="text-align: center">ผลตรวจ</th>
                <th style="text-align: center">ep1 ep3</th>
                <th style="text-align: center">med act</th>
                <th style="text-align: center">fu dot</th>
                <th style="text-align: center">fu 3</th>
                <th style="text-align: center">fu 3_ring</th>
                <th style="text-align: center">fu 7</th>
                <th style="text-align: center">fu 14</th>
                <th style="text-align: center">fu 21</th>
                <th style="text-align: center">fu 28</th>
                <th style="text-align: center">fu 35</th>
                <th style="text-align: center">fu 42</th>
                <th style="text-align: center">fu 60</th>
                <th style="text-align: center">fu 90</th>
            </tr>
        </thead>
        <tbody>
           <?php foreach($results as $result) : ?>
            <tr>
                <td style="text-align: center" nowrap><?= ($result['treatmaent_place_detail'] == "") || ($result['treatmaent_place_detail'] == null) ? "-": $result['treatmaent_place_detail'] ?></td>
                <td style="text-align: center" nowrap><?= $result['blood_draw_date']; ?></td>
                <td style="text-align: left" nowrap><?= $result['p_name']; ?></td>
                <td style="text-align: center" nowrap><?= $result['type_patient_detail']; ?></td>
                <td style="text-align: center" nowrap><?= $result['sex'] == 'M' ? 'ชาย' :  'หญิง';  ?></td>
                <td style="text-align: center" nowrap><?= $result['age_year']; ?></td>
                <td style="text-align: center" nowrap><?= ($result['p_occupation_detail']=="")||($result['p_occupation_detail']==null)?"-":$result['p_occupation_detail']; ?></td>
                <td style="text-align: center" nowrap><?= ($result['p_site_village_name']=="")||($result['p_site_village_name']==null)?"-":$result['p_site_village_name']; ?></td>
                <td style="text-align: center" nowrap><?= ($result['p_site_mu_name']=="")||($result['p_site_mu_name']==null) ? "-" : $result['p_site_mu_name']; ?></td>
                <td style="text-align: center" nowrap><?= $result['p_site_subdistrict_name']; ?></td>
                <td style="text-align: center" nowrap><?= $result['p_site_district_name']; ?></td>
                <td style="text-align: center" nowrap><?= $result['p_site_province_name'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['result_code_detail_1']=="")||($result['result_code_detail_1']==null)?"-":$result['result_code_detail_1'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['ep1_ep3']=="")||($result['ep1_ep3']==null)?"-":$result['ep1_ep3'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['med_act']=="")||($result['med_act']==null)?"-":($result['med_act'] == "U" ? "Unknown": $result['med_act']) ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_dot']=="")||($result['fu_dot']==null)?"-":$result['fu_dot'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_3']=="")||($result['fu_3']==null)?"-":$result['fu_3'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_3_ring']=="")||($result['fu_3_ring']==null)?"-":$result['fu_3_ring'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_7']=="")||($result['fu_7']==null)?"-":$result['fu_7'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_14']=="")||($result['fu_14']==null)?"-":$result['fu_14'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_21']=="")||($result['fu_21']==null)?"-":$result['fu_21'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_28']=="")||($result['fu_28']==null)?"-":$result['fu_28'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_35']=="")||($result['fu_35']==null)?"-":$result['fu_35'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_42']=="")||($result['fu_42']==null)?"-":$result['fu_42'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_60']=="")||($result['fu_60']==null)?"-":$result['fu_60'] ?></td>
                <td style="text-align: center" nowrap><?= ($result['fu_90']=="")||($result['fu_90']==null)?"-":$result['fu_90'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

<?php
function cleanData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}
$flag = false;
foreach($results as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
}
  exit;
?>
