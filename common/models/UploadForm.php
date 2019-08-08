<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $csvFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif','mimeTypes'=>'image/jpeg,image/jpg,image/png,image/gif'],
            [['csvFile'], 'file', 'skipOnEmpty' => true],
        ];
    }

    // 上传
    public function upload($type='image')
    {
        if ($this->validate()) {
            $path = 'uploads/'.$type.'/'.date('Ymd');
            if(!is_dir($path)){
                mkdir($path,0755,true);
            }
            $file = $type.'File';
            $fileName = $path.'/'.uniqid().'.' .$this->$file->extension;
            $this->$file->saveAs($fileName);
            return json_encode(['status'=>200,'path'=>'/'.$fileName]); // uploads目录前加'/',否则url美化后无法正常显示
        }else{
            return json_encode(['status'=>100,'msg'=>'上传失败']);
        }
    }
}