<?php
/**
 * Created by PhpStorm.
 * User: zhufeng04
 * Date: 2018/8/23
 * Time: 下午10:22
 */
require_once dirname(dirname(__DIR__)).'/index.php';
/*$arrSpecial = file_get_contents('./professional.txt');
$pattern = '/<a.*?id=\"(.*?)\".*?name=\"xkmlChoose\".*?>(\S*?)<\/a>/';
preg_match_all($pattern, $arrSpecial, $matches);


$pattern1 = '/<div.*?class=\"liL\".*?id=\"(.*?)\">(\S*?)\(.*?\)/';
preg_match_all($pattern1, $arrSpecial, $matches2);

$pattern2 = '/.*?target=\"_blank\".*?id=\"(.*?)\".*?style=\"display: inline;\">(\S*?)<\/a><\/li>/';
preg_match_all($pattern2, $arrSpecial, $matches3);


$tmpData = [];

foreach ($matches[1] as $key1 => $level1){
    $cate[$level1] = [
        'code' => $level1,
        'label' => $matches[2][$key1],
        'childred' => []
    ];
    foreach ($matches2[1] as $key2 => $level2){
        $strVal = stripos($level2, $level1);
        if ($strVal !== 0){
            break;
        }
        $cate[$level1]['childred'][$level2] = [
            'code' => $level2,
            'label' => $matches2[2][$key2],
            'childred' => [],
        ];

        foreach ($matches3[1] as $key3 => $level3){
            $strVal1 = stripos($level3, $level2);
            if ($strVal1 !== 0){
                break;
            }
            $cate[$level1]['childred'][$level2]['childred'][] = [
                'code' => $level3,
                'label' => $matches3[2][$key3],
            ];
            unset($matches3[1][$key3]);
        }
        unset($matches2[1][$key2]);
    }
}

echo json_encode($cate);
exit;*/
$specialData = file_get_contents('./special_data.txt');
$arrSpecialData = json_decode($specialData, true);
$i = 0;
foreach ($arrSpecialData as $level1){

    $object = [
        'special_code' => $level1['code'],
        'name' => $level1['label'],
        'parent_id' => 0,
        'level' => 1,
    ];
    $level1ParentId = create('gk_specialty', $object);
    foreach ($level1['childred'] as $level2){
        $object1 = [
            'special_code' => $level2['code'],
            'name' => $level2['label'],
            'parent_id' => $level1ParentId,
            'level' => 2,
        ];
        $level2ParentId = create('gk_specialty', $object1);
        foreach ($level2['childred'] as $level3){
            $object2 = [
                'special_code' => $level3['code'],
                'name' => $level3['label'],
                'parent_id' => $level2ParentId,
                'level' => 3,
            ];
            create('gk_specialty', $object2);
        }
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
