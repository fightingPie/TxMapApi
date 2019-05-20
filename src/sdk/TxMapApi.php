<?php
/**
 * Created by PhpStorm.
 * User: pie
 * Date: 2019/4/30
 * Time: 下午2:22
 */

namespace src\sdk;

use src\lib\HttpCurl;
use Exception;


class TxMapApi
{

    /**
     * @var Config
     */
    private $configuration;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->configuration = $config;
    }

    /**
     * 合并查询参数
     * @param array $data
     * @return array
     */
    private function mergeData($data)
    {
        $opt = [
            'key' => $this->configuration->getApiKey(),
        ];
        $opt = array_merge($opt, $data);
        return $opt;
    }

    /**
     *
     * @param type $url
     * @param type $param
     * @return boolean
     */
    private function apiGet($url, $param)
    {
        $param = $this->mergeData($param);
        $result = HttpCurl::get($this->configuration->getApi() . $url, $param, 30);
        try {
            $res = json_decode($result, true);
        } catch (Exception $e) {
            return false;
        }
        if ($res && $res['status'] == 0) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     * 参考 http://lbs.qq.com/webservice_v1/guide-suggestion.html
     * 根据关键词搜索地点
     * 关键词输入提示
     * @param type $param keyword:关键词   region:城市范围   region_fix:0当前城市无结果自动扩散到全国 1固定在当前城市   policy:检索策略
     * @return type
     */
    public function getPlaceByKeyword($param = [])
    {
        $data = $this->apiGet($this->configuration->getApiKeyword(), $param);
        if ($data) {
            try {
                if (!empty($data['data'])) {
                    return $data['data'];
                } else {
                    return [];
                }
            } catch (Exception $e) {
                return [];
            }
        } else {
            return [];
        }
    }

    /**
     * 参考 http://lbs.qq.com/webservice_v1/guide-gcoder.html
     * 根据地址显示附近地点列表
     * 逆地址解析(坐标位置描述)
     * @param type $param
     * @return type
     */
    public function getNearPlace($param = [])
    {
        $data = self::apiGet($this->configuration->getApiGeocoder(), $param);
        if ($data) {
            try {
                if (!empty($data['result']['poi_count'])) {
                    return $data['result']['pois'];
                } else {
                    return [];
                }
            } catch (Exception $e) {
                return [];
            }
        } else {
            return [];
        }
    }


    /**
     * 参考 http://lbs.qq.com/webservice_v1/guide-gcoder.html
     * 根据地址显示附近地点列表
     * 逆地址解析(坐标位置描述)
     * @param type $param
     * @return type
     */
    public function getLocation($param = [])
    {
        $data = self::apiGet($this->configuration->getApiGeocoder(), $param);
        if ($data) {
            try {
                if (!empty($data['result']['location'])) {
                    return $data['result']['location'];
                } else {
                    return [];
                }
            } catch (Exception $e) {
                return [];
            }
        } else {
            return [];
        }
    }

    /**
     * 参考 http://lbs.qq.com/webservice_v1/guide-distance.html
     * 计算两地行驶距离 一对多
     * 距离计算
     * @param array $param mode:计算方式：driving[驾车]、walking[步行]   from:起点坐标，格式：lat,lng;lat,lng...   to:终点坐标，格式：lat,lng;lat,lng...
     * @return array
     */
    public function calcDistance($param = [], $flag = false)
    {
        $res = self::apiGet($this->configuration->getApiDistance(), $param);
        if ($res) {
            return $res['result']['elements'];
        } else {
            return [];
        }
    }

    /**
     * 参考 https://lbs.qq.com/webservice_v1/guide-distancematrix.html
     * 计算两地行驶距离矩阵 多对多
     * 距离计算
     * @param array $param mode:计算方式：driving[驾车]、walking[步行]   from:起点坐标，格式：lat,lng;lat,lng...   to:终点坐标，格式：lat,lng;lat,lng...
     * @return array
     */
    public function distanceMatrix($param = [], $flag = false)
    {
        $res = self::apiGet($this->configuration->getApiDistance().'matrix', $param);
        if ($res) {
            return $res['result']['rows'];
        } else {
            return [];
        }
    }

    /**
     * 参考 http://lbs.qq.com/webservice_v1/guide-road.html
     * 路线规划返回规划路线列表
     * 路线规划服务
     * @param array $param
     * mode:计算方式：driving[驾车]、walking[步行]、bicycling[骑行]、bicycling[骑行]
     * from:起点坐标，格式：lat,lng
     * to:终点坐标，格式：lat,lng
     * from_poi:(非必填)起点POI ID，腾讯地图地点唯一id由其他接口如关键词查询返回，格式：4077524088693206111
     * to_poi:(非必填)终点POI ID，腾讯地图地点唯一id由其他接口如关键词查询返回，格式：5371594408805356897
     * 更多参数见参考
     * @return array
     */
    public function routePlanning($param = [])
    {
        if (empty($param['mode'])) {
            return false;
        }
        $modes = ['driving', 'walking', 'bicycling', 'transit'];
        if (in_array($param['mode'], $modes)) {
            $apiurl = $this->configuration->getApiDirection() . $param['mode'] . '/';
        } else {
            return false;
        }
        unset($param['mode']);
        $res = self::apiGet($apiurl, $param);
        if ($res) {
            if (!empty($res['result']['routes'])) {
                return $res['result']['routes'];
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * polyline 转坐标点
     * @param string $polyline
     * @return array
     */
    public static function polyline($polyline = '')
    {
        //转换[127.496637,50.243916,-345,-1828,19867,-26154] 为 [lat,lng,lat,lng,lat,lng...]
        $len = count($polyline);
        if ($len > 2) {
            for ($i = 2; $i < $len; $i++) {
                $polyline[$i] = $polyline[$i - 2] + $polyline[$i] / 1000000;
            }
        }
        //根据需要转换格式

        return $polyline;
    }
}