<?php
namespace mobile\controllers;
use backend\models\AdminMember;
use backend\models\UploadForm;
use Yii;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\Controller;
use yii\web\UploadedFile;

class PublicController extends Controller
{
   /**
    *清除缓存
    */
    public $enableCsrfValidation=false;
    public function actionClearCache()
    {
        $cache=Yii::$app->cache;
        if($cache->flush()){
            echo json_encode(['status'=>1]);
        }
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


    /**
     * 上传图片
     */
    public function actionUpload()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file && $model->validate()) {
                $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
            }
        }
    }

    public function actionFile()
    {
        $uploaddir = 'uploads/img/';
        $info=pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
        $dir=$uploaddir .date('Ymd').rand(10000,99999).'.'.$info;
        if (move_uploaded_file($_FILES['file']['tmp_name'],$dir)) {
            $re['dir']='/'.$dir;
            $re['msg']="上传成功";
            $re['status']=200;
            echo $re['dir'];
        } else {
            $re['msg']="上传失败";
            echo json_encode($re['msg']);
        }
    }

    /*
     * 截取并处理字符串
     * $str 处理的字符串
     * $len 截取的长度
     * $add 后面是否加...
     * */
    public static function subStr($str,$len=0,$add=true){
        if( $len < mb_strlen($str,'utf8') && $len && $add) {
            $str = mb_substr($str,0,$len,'utf-8').'...';
        } else {
            $str = mb_substr($str,0,$len,'utf-8');
        }
        return $str;
    }

    /**
     * 下载文件
     */
    public function actionDownloadFile(){
        ob_end_clean();
        $file = yii::$app->request->get('file');
        //文件绝对路径
        $path_name = ROOT.$file;
        $save_name=time();
        $hfile = fopen($path_name, "rb") or die("Can not find file: $path_name\n");
        Header("Content-type: application/octet-stream");
        Header("Content-Transfer-Encoding: binary");
        Header("Accept-Ranges: bytes");
        Header("Content-Length: ".filesize($path_name));
        Header("Content-Disposition: attachment; filename=\"$save_name\".csv");
        while (!feof($hfile)) {
            echo fread($hfile, 32768);
        }
        fclose($hfile);
    }

    public function actionExcel()
    {
        $uploaddir = 'uploads/excel/';
        $info=pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
        $dir=$uploaddir .date('Ymd').rand(10000,99999).'.'.$info;
        if (move_uploaded_file($_FILES['file']['tmp_name'],$dir)) {
            $re['dir']='/'.$dir;
            $re['msg']="上传成功";
            $re['status']=100;
            return $re['dir'];
        } else {
            $re['msg']="上传失败";
            $re['status']=200;
            return false;
        }
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

    // 获取短链接
    public static function getShortUrl($longUrl)
    {
        $source = Yii::$app->params['xl']['app_key'];
        $apiUrl = "http://api.t.sina.com.cn/short_url/shorten.json?source={$source}&url_long=".urlencode($longUrl);
        $res = self::curl($apiUrl);
        $res = json_decode($res,true);
        $shortUrl = $res[0]['url_short'];
        if($shortUrl){
            return $shortUrl;
        }
        return false;
    }

    /**
     * 获取下级用户id
     * @param $members
     * @return array
     */
    public static function getSonIds($members)
    {
        static $ids = [];
        $invitations = [];
        foreach ($members as $member) {
            array_push($ids, $member->id);
            array_push($invitations, $member->invitation);
        }
        $sonMembers = AdminMember::find()->where(['in', 'bei_invitation', $invitations])->all();
        if ($sonMembers) {
            self::getSonIds($sonMembers);
        }
        return $ids;
    }

}

