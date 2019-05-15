<?php
/**
 * Created by PhpStorm.
 * User: pie
 * Date: 2019/5/15
 * Time: 下午1:40
 */

namespace src\sdk;


class Config
{
    private $api_key;
    private $api = 'http://apis.map.qq.com/ws/';
    private $api_api_geocoder = 'geocoder/v1/';
    private $api_api_keyword = 'place/v1/suggestion';
    private $api_api_distance = 'distance/v1/';
    private $api_api_direction = 'direction/v1/';

    /**
     * Config constructor.
     * @param $api_key
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @return mixed
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @return mixed
     */
    public function getApiGeocoder()
    {
        return $this->api_api_geocoder;
    }

    /**
     * @return mixed
     */
    public function getApiKeyword()
    {
        return $this->api_api_keyword;
    }

    /**
     * @return mixed
     */
    public function getApiDistance()
    {
        return $this->api_api_distance;
    }

    /**
     * @return mixed
     */
    public function getApiDirection()
    {
        return $this->api_api_direction;
    }
}