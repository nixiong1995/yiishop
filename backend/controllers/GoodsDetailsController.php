<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Controller;

class GoodsDetailsController extends Controller
{
    public function actionIndex($id){
        $model1=Goods::findOne(['id'=>$id]);//商品表模型
        $model2=GoodsIntro::findOne(['goods_id'=>$id]);//商品详情表模型
        $models3=GoodsGallery::find()->where(['goods_id'=>$id])->all();//商品图片表模型
        return $this->render('index',['model1'=>$model1,'model2'=>$model2,'models3'=>$models3]);

    }
    //验证访问权限
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['login','logout','captcha','error'],
            ]
        ];
    }
}