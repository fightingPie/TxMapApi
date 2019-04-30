<?php
/**
 * Created by PhpStorm.
 * User: pie
 * Date: 2019/4/30
 * Time: 下午2:43
 */

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
use src\sdk\TxMapApi;

//关键词搜索
$param = [
    'keyword' => '美食',
    'region_fix' => 1,
    'policy' => 0,
    'region' => '常州',
];
$data1 = TxMapApi::getPlaceByKeyword($param);
//通过经纬返回地址名称
$param = [
    'location' => '39.984154,116.307490',
];
$data2=TxMapApi::getNearPlace($param);
var_dump($data2);


//距离计算
$param = [
    'from' => '39.983171,116.308479',
    'to' => '39.996060,116.353455;39.949227,116.394310',
    'mode' => 'driving',
];
$data3=TxMapApi::calcDistance($param);
//路线规划服务
$param = [
    'from' => '39.915285,116.403857',
    'to' => '39.915285,116.803857',
    'mode' => 'driving',
];
$data4 = TxMapApi::routePlanning($param);
var_dump($data4);
