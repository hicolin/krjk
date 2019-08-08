<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\ForbiddenHttpException;
use backend\models\AdminLog;
use common\utils\CommonFun;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;
use yii\web\UploadedFile;
use backend\models\UploadForm;
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
        if(!is_dir($uploaddir)){
            mkdir($uploaddir,755);
        }
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

}

?>