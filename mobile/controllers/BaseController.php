<?php
namespace mobile\controllers;

use backend\models\AdminMember;
use backend\models\AdminNotice;
use common\models\WeChat;
use Yii;

/**
 * Site controller
 */

class BaseController extends MobileController
{

    public $user ;

    public function init()
    {
        parent::init();
        $userModel = $this->checkLogin();
        if (!$userModel) {
            // 没有登录状况下 进行微信登录
//           if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
//                return $this->wxLogin();
//           }else{
            $uid = Yii::$app->request->get('uid');
            $pid = Yii::$app->request->get('pid');
            $type = Yii::$app->request->get('type');
            $url = Yii::$app->urlManager->createAbsoluteUrl(['index/login', 'uid' => $uid, 'pid' => $pid, 'type' => $type]);
            return $this->redirect($url);
//           }
        } else {
//            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
//                if ($userModel->openid) {
//                    $UserInfo = $this->getUserInfo($userModel->openid);
//                    if ($UserInfo->subscribe == 1) {
//                        $member = AdminMember::findOne($userModel->id);
//                        $member->subscribe = 1;
//                        if ($member->save(false)) {
//                            $this->user = $userModel;
//                        }
//                    } else {
//                        if ($UserInfo->subscribe == 0) {
//                            $member = AdminMember::findOne($userModel->id);
//                            $member->subscribe = 0;
//                            if ($member->save(false)) {
//                                $this->user = $userModel;
//                            }
//                        }
//                    }
//                } else {
//                    $this->wxLogin();
//                }
//            } else {
            $this->user = $userModel;
//            }
        }
    }



    /**
     * 拉取用户信息，UnionID机制
     */
    public  function getUserInfo($openid)
    {
        $weChat = new WeChat();
        $access_token = $weChat->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode($this->file_get_contents_safe($url));
        return $data;
    }

    /**
    * 兼容file_get_contengs 请求Https出错的情况
    */
    protected function file_get_contents_safe($url){ 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        $output = curl_exec($ch);  
        curl_close($ch); 
        return $output;
    }

    // 记录私信
    public static function writeNotice($user_id,$title,$content)
    {
        $notice = new AdminNotice();
        $notice->user_id = $user_id;
        $notice->title = $title;
        $notice->content = $content;
        $notice->create_time = time();
        $notice->save(false);
    }
}
