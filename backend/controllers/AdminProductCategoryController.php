<?php
/**
 * User: Colin
 * Time: 2018/11/16 13:52
 */

namespace backend\controllers;

use backend\models\AdminProductCategory;
use Yii;
use yii\data\Pagination;

class AdminProductCategoryController extends BaseController
{
    public $layout = "lte_main";

    // 列表
    public function actionIndex()
    {
        $query = AdminProductCategory::find();
        $querys = Yii::$app->request->get('query');
        if ($querys['name']) {
            $query = $query->andWhere(['like', 'name', $querys['name']]);
        }
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => '10',
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $categories = $query
            ->orderby('create_time desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()->all();
        return $this->render('index', [
            'model' => $categories,
            'pages' => $pagination,
            'query'=>$querys,
        ]);
    }

    // 添加
    public function actionCreate()
    {
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $model = new AdminProductCategory();
            $model->name = $post['name'];
            $model->sort = (int)$post['sort'];
            $model->create_time = time();
            if($model->save(false)){
                return $this->json(200,'添加成功');
            }
            return $this->json(100,'添加失败');
        }
    }

    // 更新
    public function actionUpdate()
    {
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $attributes = ['name'=>$post['name'],'sort'=>$post['sort']];
            $condition = ['id'=>$post['id']];
            $res = AdminProductCategory::updateAll($attributes,$condition);
            if($res || $res === 0){
                return $this->json(200,'修改成功');
            }
            return $this->json(100,'修改失败');
        }
    }
    
    // 删除
    public function actionDelrecord()
    {
        if(Yii::$app->request->isAjax){
            $ids = Yii::$app->request->get('ids');
            $res = AdminProductCategory::deleteAll(['in','id',$ids]);
            if($res){
                return $this->json(200,'删除成功');
            }
            return $this->json(100,'删除失败');
        }
    }


}