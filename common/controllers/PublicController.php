<?php
namespace common\controllers;
use backend\models\AdminMember;
use backend\models\AdminSetting;
use common\models\WeChat;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
class PublicController extends Controller
{

    public static $user_id;
    public static $user_info;
    /**
     * init
     */
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        self::$user_id = Yii::$app->session->get('user_id');
        self::$user_info = AdminMember::findOne($this->user_id);
    }

    /**
     *过滤公共方法
     * $type  1是纯文本过滤，2是HTML过滤
     */
    public static function filter($text, $type=1)
    {
        if($type ==1 ) {
            return Html::encode($text);
        }else if($type == 2) {
            return HtmlPurifier::process($text);
        }
    }

    public static function filter_decode($text,$type=1){
        if($type==1){
            return htmlspecialchars_decode($text);
        }else{
             return HtmlPurifier::process($text);
        }
    }
    /**
     *生成邀请码
     */
    public static function getInvitation()
    {
        $code = substr(md5(uniqid(rand(),1)),0,16);
        if(AdminMember::find()->where(['invitation'=>$code])->one()) {
            self::getInvitation();
        }
        return $code;
    }

    public static function file($file,$name='pic')
    {
        $uploaddir = 'uploads/img/';
        $info=pathinfo($file['name'][$name],PATHINFO_EXTENSION);
        $dir=$uploaddir .date('Ymd').rand(10000,99999).'.'.$info;
        if (move_uploaded_file($file['tmp_name'][$name],$dir)) {
            $re['dir']='/'.$dir;
            $re['msg']="上传成功";
            $re['status']=200;
            return $re['dir'];
        } else {
            $re['msg']="上传失败";
            return false;
        }
    }

    /**
     * 截取并处理字符串
     * $str 处理的字符串
     * $len 截取的长度
     * $add 后面是否加...
     */
    public static function subStr($str,$len=0,$add=true){
        if( $len < mb_strlen($str,'utf8') && $len && $add) {
            $str = mb_substr($str,0,$len,'utf-8').'...';
        } else {
            $str = mb_substr($str,0,$len,'utf-8');
        }
        return $str;
    }

    /**
     * 获取设备信息
     * @return integer
     * 1,安卓，2,ios,3,其他
     */
    public static function getSystem()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        if($is_android) {
            return 1;
        }else if($is_iphone) {
            return 2;
        }else {
            return 3;
        }
    }
    
    public static function build_form($url, $data){
        $sHtml = "<!DOCTYPE html><html><head><title>Waiting...</title>";
        $sHtml.= "<meta http-equiv='content-type' content='text/html;charset=utf-8'></head>
      <body><form id='lakalasubmit' name='lakalasubmit' action='".$url."' method='POST'>";
        foreach($data as $key => $value){
            $sHtml.= "<input type='hidden' name='".$key."' value='".$value."' style='width:90%;'/>";
        }
        $sHtml .= "</form>";
        $sHtml .= "<script>document.forms['lakalasubmit'].submit();</script></body></html>";
        exit($sHtml);
    }

    /**
     * 发送短信
     */
    public static function sendSms($tel,$param,$temp,$sign='卡农社区')
    {
        require_once ROOT.'/sms/TopSdk.php';
        //$code=rand(100000,999999);
        $appkey = self::getSysInfo(5);
        $secret = self::getSysInfo(6);
        $c = new \TopClient;
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $c->format = 'json';
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        //$req->setExtend($code);
        $req->setSmsType('normal');
        $req->setSmsFreeSignName($sign); //发送的签名
        $req->setSmsParam($param);//根据模板进行填写
        $req->setRecNum($tel);//接收着的手机号码
        $req->setSmsTemplateCode($temp);//短信模板
        $resp = $c->execute($req);
        return $resp->result->err_code;
    }

    // 发送短信接口
    public static function setCodes($tel,$random)
    {
        $statusStr = array(
            "0" => "短信发送成功",
            "-1" => "参数不全",
            "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
            "30" => "密码错误",
            "40" => "账号不存在",
            "41" => "余额不足",
            "42" => "帐户已过期",
            "43" => "IP地址限制",
            "50" => "内容含有敏感词"
        );
        $smsapi = "http://api.smsbao.com/";
        $user = ""; //短信平台帐号
        $pass = md5(""); //短信平台密码
        $content="【】".$random."为您的验证码，请于3分钟内填写。如非本人操作，请忽略本短信。";//要发送的短信内容
        $phone = $tel;//要发送短信的手机号码
        $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
        $result =file_get_contents($sendurl) ;
        if($result=='0'){
            return true;
        } else {
            return false;
        }
    }
    // 腾讯云发送短信接口
     public static function setCodes2($phone, $random)
        {
            $tpl_id = Yii::$app->params['sms']['tpl_id'];  // 通用短信模板：{1}为您的短信验证码，请于{2}分钟内填写。如非本人操作，请忽略本短信。
            $sj = 3;
            $curTime = time();
            $wholeUrl = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms?sdkappid=".Yii::$app->params['sms']['app_id']."&random=" . $random;
            // 按照协议组织 post 包体
            $data = new \stdClass();
            $tel = new \stdClass();
            $tel->nationcode = "" . "86";
            $tel->mobile = "" . $phone;
            $data->tel = $tel;
            $data->sig = hash("sha256",
                "appkey=".Yii::$app->params['sms']['app_key']."&random=" . $random . "&time="
                . $curTime . "&mobile=" . $phone, FALSE);
            $data->tpl_id = $tpl_id;
            $data->params = array($random, $sj);
            $data->time = $curTime;
            //$data->sign = '云肆网络';//如果只有一个则不需要签名
            $data->extend = '';
            $data->ext = '';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $wholeUrl);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $ret = curl_exec($curl);
            $res = json_decode($ret, true);
            if ($res['errmsg'] == 'OK') {//发送成功
                return true;
            } else {
                return false;
            }
        }



     public static function setCodesBak($tel,$param,$temp)
     {
            //发送链接（用户名，密码，手机号，内容）
        // echo  $tel;exit;
            $url = "https://sms.qianhaishuliang.com/sms_platform/receiveController?";
            $mobilePhone =$tel;
            $serviceType=$temp;
            $params=$param;
            $sys ='33';
            $sigkey ='7f79150aa903418f989e18a5cc71a4ee';
            $sige =$sys.$serviceType.$mobilePhone.$sigkey;
            $siges =strtoupper(MD5($sige)); //将字符串小写转换成大写
            $data=array
            (
                'mobilePhone'=>$mobilePhone,//手机号码
                'serviceType'=>$serviceType,//模板编号
                'asyn'=>false,//是否异步(一版传false)
                'sys' =>$sys,//调用系统编号
                'data'=>$params,
                'sige'=>$siges,//签名信息md5(sys+serviceType+mobilePhone+sigkey)
            );
            $datas =json_encode($data);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data));
            // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $ret = curl_exec($curl);
            $res = json_decode($ret, true);
            if($res['result']==0000){
                 return true;
            }else{
                 return false;
            }
    }

    /**
     * 获取配置信息
     */
    public static function getSysInfo($id)
    {
        return AdminSetting::findOne($id)->val;
    }

    /**
     * 生成菜单
     */
    public static function setMenu()
    {                                 // 购买会员url书写方式：兼容公众号菜单支付成功后的跳转
        $menu = '{
            "button":[
                {
                    "name":"APP下载",
                    "sub_button":[
                    {	
                        "type":"view",
                        "name":"购买会员",
                        "url":"'.Yii::$app->urlManager->createAbsoluteUrl(['list/buy-agent']).'"   
                    },
                    {
                        "type":"view",
                        "name":"APP下载",
                        "url":"'.Yii::$app->urlManager->createAbsoluteUrl(['list/app-download']).'"
                    }]
                },
                {	
                    "type":"click",
                    "name":"我的代言码",
                    "key":"QRCODE"
                },
                {
                        "type":"view",
                        "name":"联系客服",
                        "url":"'.Yii::$app->urlManager->createAbsoluteUrl(['list/wx']).'"
                    }
            ]
        }';
        $weChat = new WeChat();
        return $weChat->menuSet($menu);
    }

    /**
     * @param $temp
     * @param $openid
     * @param $url
     * @param $data
     * @return bool
     */
    public static function sendTempMsg($temp,$openid,$data,$url='')
    {
        $post_data = [
            "touser"=>$openid,
            "template_id"=>$temp,
            "url"=>$url,
            "data"=> $data
        ];
        $weChat = new WeChat();
        $post_data = json_encode($post_data);
        return $weChat->sendTempInfo($post_data);
    }

    /**
     * 读取网络上面图片，保存到本地
     * @param $url
     * @param string $filename
     * @return bool|string
     */
    public static function createImage($url, $filename = "")
    {
        $head_url = substr($url,0,strripos($url, "/"))."/46";
        $img = file_get_contents($head_url);
        file_put_contents($filename,$img);
        return $filename;//返回新的文件名

        /*if ($url == ""):return false;
        endif;
        //如果$url地址为空，直接退出
        if ($filename == "") {
            //如果没有指定新的文件名
            $ext = strrchr($url, ".");
            //得到$url的图片格式
            if ($ext != ".gif" && $ext != ".jpg"):return false;
            endif;
            //如果图片格式不为.gif或者.jpg，直接退出
            $filename = date("dMYHis") . $ext;
            //用天月面时分秒来命名新的文件名
        }
        ob_start();//打开输出
        readfile($url);//输出图片文件
        $img = ob_get_contents();//得到浏览器输出
        ob_end_clean();//清除输出并关闭
        //$size = strlen($img);//得到图片大小
        $fp2 = @fopen($filename, "a");
        fwrite($fp2, $img);//向当前目录写入图片文件，并重新命名
        fclose($fp2);
        return $filename;//返回新的文件名*/
    }

    /**
     * 生成二维码，保存到本地
     * @param string $url
     * @param bool $file_name
     */
    public static function qrCode($url='',$file_name=false,$size=13)
    {
        require ROOT.'/phpqrcode/qrlib.php';
        //设置 header 头,直接输出图片
        Yii::$app->response->headers->set('Content-Type', 'image/png');
        //根据参数生成二维码 , 将其第二个参数值设为 false ,也就是不输出图片文件
        \QRcode::png($url, $file_name, "L", $size, 1);
        //die();
    }

    public static function qrCode2($url='',$file_name=false,$size=11)
    {
        require ROOT.'/phpqrcode/qrlib.php';
        //设置 header 头,直接输出图片
        Yii::$app->response->headers->set('Content-Type', 'image/png');
        //根据参数生成二维码 , 将其第二个参数值设为 false ,也就是不输出图片文件
        \QRcode::png($url, $file_name, "L", $size, 1);
        //die();
    }

    // 推广下级
    public static function qrCode3($url='',$file_name=false,$size=16)
    {
        require ROOT.'/phpqrcode/qrlib.php';
        //设置 header 头,直接输出图片
        Yii::$app->response->headers->set('Content-Type', 'image/png');
        //根据参数生成二维码 , 将其第二个参数值设为 false ,也就是不输出图片文件
        \QRcode::png($url, $file_name, "L", $size, 1);
        //die();
    }
    /**
     * 判断用户是否关注公众号
     * @param $openid
     * @return int
     */
    public static function isSubscribe($openid)
    {
        $weChat = new WeChat();
        $access_token = $weChat->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode(file_get_contents($url));
        $subscribe = 0;
        if(isset($data->subscribe)) {
            $subscribe = $data->subscribe;
        }
        return $subscribe;
    }

    // curl请求
    public static function curl($url, $ifpost = 0, $datafields = '', $cookiefile = '', $v = false) {
        $header = array("Connection: Keep-Alive","Accept: text/html, application/xhtml+xml, */*",
            "Pragma: no-cache", "Accept-Language: zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3","User-Agent: Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0)");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $v);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $ifpost && curl_setopt($ch, CURLOPT_POST, $ifpost);
        $ifpost && curl_setopt($ch, CURLOPT_POSTFIELDS, $datafields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        $cookiefile && curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        $cookiefile && curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    // json格式的POST请求
    public static function httpRequestPostJson($url, $json_data)
    {
        $curlObj = curl_init(); //初始化curl
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
                'Accept:application/json;charset=UTF-8',
                'Content-Type: application/json',    // json格式
                'Content-Length: ' . strlen($json_data))
        );
        curl_setopt($curlObj, CURLOPT_URL, $url); //设置网址
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //将curl_exec的结果返回
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curlObj, CURLOPT_HEADER, 0); //是否输出返回头信息
        curl_setopt($curlObj, CURLOPT_POST, 1);// post数据
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $json_data);// post的变量
        $response = curl_exec($curlObj); //执行
        curl_close($curlObj); //关闭会话
        return $response;
    }

    // 意扬对外接口(保险)
    public static function yyangApi($name, $tel, $idCard)
    {
        $apiUrl = Yii::$app->params['yyang']['api_url'];
        $siteId = Yii::$app->params['yyang']['site_id'];
        $csCode = Yii::$app->params['yyang']['cs_code'];
        $secret = Yii::$app->params['yyang']['secret'];
        $data = [];
        $addTime = date('Y-m-d H:i:s', time());
        $data['siteid'] = $siteId;
        $data['cscode'] = $csCode;
        $data['mobile'] = $tel;
        $data['realname'] = $name;
        $data['idcard'] = $idCard;
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data['need_gadget'] = 0;
        $data['leads_type'] = 1;
        $data['add_time'] = $addTime;
        $data['sign'] = md5($secret . $addTime);
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $res = self::httpRequestPostJson($apiUrl, $data);
        $res = json_decode($res, true);
        return $res;
    }


    /**
     * 校验身份证号码
     * @param $number
     * @return bool
     */
    public static function isIdCard($number)
    {
        //加权因子
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码串
        $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        //按顺序循环处理前17位
        $sigma = 0;
        for ($i = 0; $i < 17; $i++) {
            //提取前17位的其中一位，并将变量类型转为实数
            $b = (int)$number{$i};
            //提取相应的加权因子
            $w = $wi[$i];
            //把从身份证号码中提取的一位数字和加权因子相乘，并累加
            $sigma += $b * $w;
        }
        //计算序号
        $snumber = $sigma % 11;
        //按照序号从校验码串中提取相应的字符。
        $check_number = $ai[$snumber];
        if ($number{17} == $check_number) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 打乱二维数组
     * @param $list
     * @return array
     */
    public static function shuffle_assoc($list)
    {
        if (!is_array($list)) return $list;
        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key)
            $random[$key] = $list[$key];
        return $random;
    }

    /**
     * 获取等级对应的佣金
     * @param $money
     * @param $grade
     * @param $num
     * @return int|string
     */
    public static function commission($money, $grade, $num)
    {
        $rate = explode(',', PublicController::getSysInfo(7));    //1级 金牌
        $rate2 = explode(',', PublicController::getSysInfo(12));  //2级 银牌
        $rate3 = explode(',', PublicController::getSysInfo(13)); //3级  铜牌 0 1
        if ($grade != 0) {
            if ($grade == 3) { //  金牌 一 二 三 级
                foreach ($rate as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
            if ($grade == 2) { //  银牌 一 二 三 级
                foreach ($rate2 as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
            if ($grade == 1) { //  铜牌 一 二 三 级
                foreach ($rate3 as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
        } else {
            $commisssion = 0;
        }
        return $commisssion;
    }

}
