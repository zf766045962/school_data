<?php
/**
 * Created by PhpStorm.
 * User: zhufeng04
 * Date: 2018/8/22
 * Time: 下午4:01
 */
require_once dirname(dirname(__DIR__)).'/index.php';
$arr = ['池州学院' => 0, '海南医学院' => 0, '海南热带海洋学院' => 0, '蚌埠学院' => 0];
for ($i = 1; $i <= 128; $i++){

    if ($i < 25){
        $fileName = $i.'页';
    }else{
        $fileName = $i;
    }
    $fileInfo = file_get_contents('./school/'.$fileName);

    $arr = json_decode($fileInfo, true);

    $rows = $arr['rows'];
    foreach ($rows as $val){


        $object = [
            'school_code' => 0,
            'school_name' => trim($val['yxmc']),
            'school_subjection' => $val['lsbmId'],
            'school_education' => $val['bxxzId'],
            'school_nature' => $val['bxccId'],
            'province_id' => $val['szsfId'],
            'city_id' => $val['csId'],
            'school_type' => $val['yxflId'],
            'school_pm' => $val['yxpm'],
            'school_logo' => $val['yxlogo'],
            'school_establish_time' => $val['cjsj'],
            'url' => $val['gfwz'],
            'add_time' => date("Y-m-d H:i:s"),
            'update_time' => date("Y-m-d H:i:s"),

        ];
        if(empty($val['bxccId'])){
            Logger::log('nature.log', ['name' => $val['yxmc']]);
        }
        if (empty($val['yxflId'])){
            Logger::log('school.log', ['name' => $val['yxmc']]);
        }
        //个1就是4个都有1000就是只有211，985，国防生，卓越计划
        $feature = '';

        //* content+=o.sfjbw == 1?"985&nbsp;":"";
        //* content+=o.sfeyy == 1?"211&nbsp;":"";
        //* content+=o.sfygfs == 1?"国防生&nbsp;":"";
        //* content+=o.sfyzyjh == 1?"卓越计划":"";

        if ($val['sfeyy'] == 1){
            $feature .= '1';
        }else{
            $feature .= '0';
        }
        if ($val['sfjbw'] == 1){
            $feature .= '1';
        }else{
            $feature .= '0';
        }
        if ($val['sfygfs'] == 1){
            $feature .= '1';
        }else{
            $feature .= '0';
        }
        if ($val['sfyzyjh'] == 1){
            $feature .= '1';
        }else{
            $feature .= '0';
        }
        $object['school_feature'] = $feature;
        $parentId = create('gk_school', $object);

        $object1 = [
            'school_id' => $parentId,
            'info' => json_encode($val),
        ];
        create('gk_school_otherinfo', $object1);
    }
}
function create($table, $object)
{
    $result = DB::insert($table)
                ->columns(array_keys($object))
                ->values(array_values($object))
                ->execute();
    if (empty($result)){
        return false;
    }else{
        return $result[0];
    }
}