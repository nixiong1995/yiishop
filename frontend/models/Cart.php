<?php
namespace frontend\models;
use backend\models\Goods;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord{

    //验证规则
    public function rules()
    {
        return [
            [['goods_id','amount'],'required']
        ];
    }

    //获取商品名字
    public function getGoods(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
}