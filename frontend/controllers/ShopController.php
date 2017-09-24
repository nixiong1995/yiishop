<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\Controller;


class ShopController extends Controller
{
    //商城首页
    public function actionIndex(){
        $categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->renderPartial('index',['categorys1'=>$categorys1]);
    }

    //商品列表页
    public function actionList($category_id){
        $caregory=GoodsCategory::findOne(['id'=>$category_id]);
        $query=Goods::find()->where(['status'=>1]);
        if($caregory->depth==2){
            //三级分类
            $query->andWhere(['goods_category_id'=>$category_id]);
        }elseif($caregory->depth==1){
            //二级分类
            $ids=$caregory->children()->select('id')->andWhere(['depth'=>2])->column();
            $query->andWhere(['in','goods_category_id',$ids]);
        }else{
            //一级分类
            $ids=$caregory->children()->select('id')->andWhere(['depth'=>2])->column();
            $query->andWhere(['in','goods_category_id',$ids]);
        }
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>10,
        ]);
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();

        return $this->renderPartial('list',['pager'=>$pager,'models'=>$models]);
    }

    //商品详情页
    public function actionGoods($id){
        $good=Goods::findOne(['id'=>$id]);//商品基本信息
        $imgs=GoodsGallery::find()->where(['goods_id'=>$id])->all();//商品图片墙
        $info=GoodsIntro::findOne(['goods_id'=>$id]);
       return $this->renderPartial('detailed',['good'=>$good,'imgs'=>$imgs,'info'=>$info]);
    }


}