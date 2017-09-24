<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class OrderGoods extends ActiveRecord
{

    //验证规则
    public function rules()
    {
        return [
            [['order_id','goods_id','goods_name','logo','price','amount','total'],'required'],
            [['order_id','goods_id','price','total'],'number'],
        ];
    }

    //建立与商品的关系
    public function getOrder(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
    }

}