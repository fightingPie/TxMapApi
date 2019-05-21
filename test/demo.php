<?php
/**
 * Created by PhpStorm.
 * User: pie
 * Date: 2019/4/30
 * Time: 下午2:43
 */

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

use apie\tencentMapApi\sdk\TxMapApi;

$api = new TxMapApi('QR7BZ-X2S3X-FZV4A-7SQUB-ORE57-RJFEF');

////关键词搜索
//$param = [
//    'keyword' => '美食',
//    'region_fix' => 1,
//    'policy' => 0,
//    'region' => '常州',
//];
//$data1 = TxMapApi::getPlaceByKeyword($param);
////通过经纬返回地址名称
//$param = [
//    'address' => '厦门湖里万达',
//];
//$data2 = $api->getLocation($param);
//var_dump($data2['lng'], $data2['lat']);
//
//
////距离计算
$param = [
    'from' => '24.48938,118.178802;24.492321,118.128212;24.579805,118.095085;24.5118,118.14577',
    'to' => '24.48938,118.178802;24.492321,118.128212;24.579805,118.095085;24.5118,118.14577',
    'mode' => 'walking',
];
$data3 = $api->distanceMatrix($param);
var_dump($data3);exit();


//$param = [
//    'address' => '厦门湖里万达',
//];
$data2 = $api->getLocation(['address' => '厦门中医院']);
//$data2 = $api->getLocation(['address' => '厦门湖里万达公交站']);
//$data2 = $api->getLocation(['address' => '厦门软件园二期西门公交站']);
var_dump($data2['lng'], $data2['lat']);

//路线规划服务
$param = [
    'from' => '24.4893454042,118.1788051128',
    'to' => '24.5041755178,118.1777858734',
    'mode' => 'walking',
];
$data4 = $api->routePlanning($param);
$polyline = $api::polyline($data4[0]['polyline']);
//var_dump($polyline);



