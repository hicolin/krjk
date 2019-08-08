<?php
/**
 * User: Colin
 * Time: 2019/3/14 16:41
 */

namespace mobile\controllers;


class Helper
{
    /**
     * 发送 POST 请求
     * @param $url
     * @param $postData
     * @return bool|string
     */
    public static function sendPost($url, $postData) {
        $postData = http_build_query($postData);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postData,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    /**
     * curl 请求
     * @param $url
     * @param int $isPost
     * @param string $dataFields
     * @param string $cookieFile
     * @param bool $v
     * @return mixed
     */
    public static function curl($url, $isPost = 0, $dataFields = '', $cookieFile = '', $v = false) {
        $header = array("Connection: Keep-Alive","Accept: text/html, application/xhtml+xml, */*",
            "Pragma: no-cache", "Accept-Language: zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3","User-Agent: Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0)");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $v);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $isPost && curl_setopt($ch, CURLOPT_POST, $isPost);
        $isPost && curl_setopt($ch, CURLOPT_POSTFIELDS, $dataFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        $cookieFile && curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        $cookieFile && curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
}