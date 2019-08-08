<?php
namespace common\models;

use backend\models\AdminMember;
use common\controllers\PublicController;
use Yii;
use yii\base\Model;
use yii\helpers\Url;
use CURLFile;
use common\models\WxTransfersConfig;

/**
 * 微信企业转账工具类
 */
class WxTransfers
{
    // 企业转账请求地址
    const TRANSFERS_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

    //获取转账信息地址
    const GETINFO_URL='https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';

    // 转账需要的配置 'wxappid','mch_id','key'
    private $_keys;

    // 转账需要的证书文件 'api_cert', 'api_key', 'rootca',请传入绝对路径！！！
    private $_cert;

    protected $log_file;

    public $error;

    // 相关配置必备参数
    protected $_parameters = array(

    );

    // 最后一次生产的订单号
    protected $_lastPartnerTradeNo;

    // 记录最后一次发送请求的结果对象
    protected $_lastResult;

    // 最后一次随机数
    protected $_lastRandNum;

    public function __construct($config)
    {
        $keys = array(
            'wxappid',
            'mch_id',
            'key'
        );
        $files = array(
            'api_cert',
            'api_key',
            'rootca'
        );

        foreach ($keys as $key) {
            try {
                $this->_keys[$key] = $config[$key];
            } catch (Exception $e) {
                throw new Exception('参数缺失：' . $key);
            }
        }

        foreach ($files as $file) {
            try {
                $cret_file = $config[$file];
                if (is_file($cret_file)) {
                    $this->_cert[$file] = $cret_file;
                }
            } catch (Exception $e) {
                throw new Exception('证书错误');
            }
        }
    }

    public function transfers($parameters){

        $this->log($parameters, 'SEND_PARAM');

        $this->setParameter('mchid', $this->_keys['mch_id']);
        $this->setParameter('mch_appid', $this->_keys['wxappid']);

        $must = array(
            'openid',
            'check_name',
            're_user_name',
            'amount',
            'desc',
            'spbill_create_ip',
        );
        foreach ($must as $key) {
            if (isset($parameters[$key]) && $parameters[$key]) {
                $this->setParameter($key, $parameters[$key]);
            } else
                if (! isset($this->_parameters[$key]) || ! $this->_parameters[$key]) {
                    $this->error = '参数缺损:' . $key;
                    return false;
                }
        }
        if (! isset($parameters['partner_trade_no'])) {
            $parameters['partner_trade_no'] = $this->getPartnerTradeNo();
        }

        $this->setParameter('partner_trade_no', $parameters['partner_trade_no']);

        $this->setParameter('nonce_str', $this->getRand(30, 3));

        $postXml = $this->_createXml();

        if (! $postXml) {
            return false;
        }
        $this->log($postXml, 'SEND_XML');

        $result = $this->curl_post_ssl(self::TRANSFERS_URL, $postXml);

        $this->log($result, 'RESULT_XML');

        if (! $result) {
            return false;
        }
        $resultObj = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);

        $this->_lastResult = $resultObj;

        if ($resultObj->return_code == 'SUCCESS') { // 成功标识
            return $resultObj->send_listid;
            if ($resultObj->result_code == 'SUCCESS') {
                return $resultObj->send_listid;
            }

            if ($resultObj->return_msg) {
                $this->error = (string) $resultObj->return_msg;
                return false;
            }

            $this->error = (string) $resultObj->err_code_des;
            return false;
        }

        if ($resultObj->return_code != 'FAIL') {
            $this->error = '返回信息格式异常';
            return false;
        }

        $this->error = (string) $resultObj->return_msg;
        return false;
    }

    /**
     * 获取转账信息
     * @param unknown $partner_trade_no
     * @return boolean|SimpleXMLElement
     */
    public function getInfo($partner_trade_no){
        $param = array(
            'nonce_str' => $this->getRand(30, 3),
            'partner_trade_no'=> $partner_trade_no ,
            'mch_id'    => $this->_keys['mch_id'],
            'appid'     => $this->_keys['wxappid'],
        );

        ksort($param);
        $unSignParaString = $this->_formatQueryParaMap($param, false);
        $param['sign'] = $this->_sign($unSignParaString, $this->_keys['key']);

        $xml = $this->arrayToXml($param);

        $this->log($xml, 'GETINFO_XML');

        $result = $this->curl_post_ssl(self::GETINFO_URL, $xml);

        if(!$result){
            return false ;
        }

        $this->log($result, 'RESULT_XML');

        $resultObj = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->_lastResult = $resultObj ;
        if($resultObj->return_code == 'SUCCESS'){//成功标识

            if($resultObj->result_code == 'SUCCESS'){
                return $resultObj ;
            }

            if($resultObj->return_msg){
                $this->error = $resultObj->return_msg ;
                return false ;
            }

            $this->error = $resultObj->err_code_des ;
            return false ;
        }

        if($resultObj->return_code != 'FAIL'){
            $this->error = '返回信息格式异常';
            return false ;
        }

        $this->error = $resultObj->return_msg ;
        return false ;
    }
    /**
     * 设置所需要的参数
     * @param  $parameter 键值数组/键
     * @param  $value 值
     * @return WxBonusApi
     */
    public function setParameter($parameter, $value = null)
    {
        if (! is_array($parameter)) {
            return $this->setParameter(array(
                $parameter => $value
            ));
        }

        foreach ($parameter as $key => $value) {
            $key = trim($key);
            $value = trim($value);
            $this->_parameters[$key] = $value;
        }
        return $this;
    }

    /**
     * 获取参数值
     * @param  $parameter 键名
     * @return multitype:
     */
    public function getParameter($parameter)
    {
        return $this->_parameters[$parameter];
    }

    /**
     * 获取随机数
     * @param number $len 随机数的位数
     * @param number $type 取值范围 1表示数字 2小写字母 4大写字母
     * @return string
     */
    public function getRand($len = 30, $type = 0)
    {
        $str = '';
        $max = - 1;

        if (! $type) {
            $type = 3;
        }

        if ($type & 1) {
            $str .= '1234567890';
            $max += 10;
        }

        if ($type & 2) {
            $str .= 'abcdefghijklmnopqrstuvwxyz';
            $max += 26;
        }

        if ($type & 4) {
            $str .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $max += 26;
        }

        $rand = '';
        for ($i = 0; $i < $len; $i ++) {
            $rand .= $str[rand(0, $max)];
        }

        return $rand;
    }

    /**
     * 生成商户的订单号
     * @return string
     */
    public function getPartnerTradeNo()
    {
        $this->_lastPartnerTradeNo = $this->_parameters['mch_id'] . date('YmdHis') . $this->getRand(4, 1); // $this->getRandNum();
        return $this->_lastPartnerTradeNo;
    }


    /**
     * 获取最后一次创建生成的订单号
     * @return string
     */
    public function getLastPartnerTradeNo()
    {
        return $this->_lastPartnerTradeNo;
    }



    /**
     * 创建XML的方法
     * @param number $retcode
     * @param string $reterrmsg
     * @return boolean|string
     */
    private function _createXml()
    {
        try {
            $sign = $this->_getSign();
            if (! $sign) {
                return false;
            }
            $this->setParameter('sign', $sign);

            return $this->arrayToXml($this->_parameters);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * 参数转换成XML
     * @param array $arr 参数数组
     * @return string
     */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 获得签名结果
     * @return boolean|Ambigous <string, boolean>
     */
    protected function _getSign()
    {
        try {

            if ($this->_checkSign() == false) { // 检查生成签名参数
                $this->error = '生成签名参数缺失！';
                $this->log(json_encode($this->_parameters, JSON_UNESCAPED_UNICODE), 'ERROR_Sign_XML');
                return false;
            }

            ksort($this->_parameters);
            $unSignParaString = $this->_formatQueryParaMap($this->_parameters, false);

            return $this->_sign($unSignParaString, $this->_keys['key']);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 检查签名所需参数是否齐全
     * @return boolean
     */
    private function _checkSign()
    {
        // return true;
        if ($this->_parameters["mch_appid"] == null ||
            $this->_parameters["mchid"] == null ||
            //$this->_parameters["device_info"] == null || 设备id
            $this->_parameters["nonce_str"] == null ||
            $this->_parameters["partner_trade_no"] == null ||
            $this->_parameters["openid"] == null ||
            $this->_parameters["check_name"] == null ||
            $this->_parameters["re_user_name"] == null ||
            $this->_parameters["desc"] == null ||
            $this->_parameters["spbill_create_ip"] == null) {
            return false;
        }
        return true;
    }

    /**
     *
     * @param  $paraMap
     * @param  $urlencode
     * @return string
     */
    private function _formatQueryParaMap($paraMap,$urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v && "sign" != $k) {
                if ($urlencode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }


    /**
     * 签名
     * @param $content 签名的字符串
     * @param $key 密钥
     * @throws Exception
     * @return string|boolean
     */
    private function _sign($content, $key)
    {
        try {
            if (null == $key) {
                $this->error = '签名key不能为空！';
                return false;
            }
            if (null == $content) {
                $this->error = '签名内容不能为空';
                return false;
            }
            $signStr = $content . "&key=" . $key;

            return strtoupper(md5($signStr));

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * cURL抓取
     *
     * @param $url 链接地址
     * @param $vars 参数
     * @param
     *          $second
     * @param
     *          $aHeader
     * @return mixed|boolean
     */
    function curl_post_ssl($url, $data, $second = 30, $aHeader = array())
    {
        $ch = curl_init();
        // 超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 这里设置代理，如果有的话
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // cert 与 key 分别属于两个.pem文件
        curl_setopt($ch, CURLOPT_SSLCERT, $this->_cert['api_cert']);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->_cert['api_key']);
        curl_setopt($ch, CURLOPT_CAINFO, $this->_cert['rootca']);
        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $this->log(json_encode($this->_cert));
            $this->error = 'aa:'.curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }

    /**
     * 获取服务器ip
     *
     * @return string
     */
    public function getServerIp()
    {
        $server_ip = '127.0.0.1';
        if (isset($_SERVER)) {
            if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']) {
                $server_ip = $_SERVER['SERVER_ADDR'];
            } elseif (isset($_SERVER['LOCAL_ADDR']) && $_SERVER['LOCAL_ADDR']) {
                $server_ip = $_SERVER['LOCAL_ADDR'];
            }
        } else {
            $server_ip = getenv('SERVER_ADDR');
        }
        return $server_ip;
    }

    /**
     * 设置日志目录文件
     *
     * @param unknown $file
     */
    public function setLogFile($file)
    {
        $this->log_file = $file;
    }

    /**
     * 写日志
     *
     * @param $msg 写入的信息
     * @param $type 日志类型作为查询标示
     */
    public function log($msg, $type)
    {
        if ($this->log_file) {
            $log = str_replace(array(
                "\r\n",
                "\r",
                "\n"
            ), array(
                "",
                "",
                ""
            ), $msg);
            error_log($type . ' ' . date('Y-m-d H:i:s') . ' ' . json_encode($log,JSON_UNESCAPED_UNICODE) . "\r\n", 3, $this->log_file);
        }
    }
}
