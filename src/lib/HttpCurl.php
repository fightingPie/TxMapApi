<?php
/**
 * Created by PhpStorm.
 * User: pie
 * Date: 2019/4/30
 * Time: 下午2:24
 */

namespace apie\tencentMapApi\lib;


class HttpCurl
{

    /**
     * combineURL
     * 拼接url
     * @param string $baseURL   基于的url
     * @param array  $keysArr   参数列表数组
     * @return string           返回拼接的url
     */
    public static function combineURL($baseURL, $keysArr) {
        if (empty($keysArr)) {
            return $baseURL;
        }
        $combined = $baseURL . "?";
        $valueArr = array();
        foreach ($keysArr as $key => $val) {
            $valueArr[] = "$key=$val";
        }
        $keyStr = implode("&", $valueArr);
        $combined .= ($keyStr);
        return $combined;
    }
    /**
     * get_contents
     * 服务器通过get请求获得内容
     * @param string $url       请求的url,拼接后的
     * @return string           请求返回的内容
     */
    public static function get_contents($url, $time = 30) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        curl_close($ch);
        //-------请求为空
        if (empty($response)) {
            return null;
        }
        return $response;
    }
    /**
     * get
     * get方式请求资源
     * @param string $url     基于的baseUrl
     * @param array $keysArr  参数列表数组
     * @return string         返回的资源内容
     */
    public static function get($url, $keysArr, $time = 30) {
        $combined = self::combineURL($url, $keysArr);
        return self::get_contents($combined, $time);
    }
    /**
     * get
     * get方式请求资源
     * @param string $url     基于的baseUrl
     * @param array $keysArr  参数列表数组
     * @return string         返回的资源内容
     */
    public static function getwithheader($url, $keysArr, $time = 30) {
        $url = self::combineURL($url, $keysArr);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        //-------请求为空
        if (empty($response)) {
            return null;
        }
        return array('header' => $httpinfo, 'res' => $response);
    }
    /**
     * post
     * post方式请求资源
     * @param string $url       基于的baseUrl
     * @param array $keysArr    请求的参数列表
     * @param int $flag         标志位
     * @return string           返回的资源内容
     */
    public static function post($url, $keysArr, $flag = 0, $time = 30) {
        $ch = curl_init();
        if (!$flag)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
    /**
     * post
     * post方式请求资源
     * @param string $url       基于的baseUrl
     * @param array $keysArr    请求的参数列表
     * @param int $flag         标志位
     * @return string           返回的资源内容
     */
    public static function postJson($url, $keysArr, $flag = 0, $time = 30) {
        $ch = curl_init();
        if (!$flag)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($keysArr))
        );
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
}