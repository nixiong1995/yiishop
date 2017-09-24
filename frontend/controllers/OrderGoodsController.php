<?php
namespace frontend\controllers;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\web\Controller;

class OrderGoodsController extends Controller{
    //我的订单列表
    public function actionMyOrder(){
        $member_id=\Yii::$app->user->id;
        $orders=Order::find()->where(['member_id'=>$member_id])->all();
        $orderIds=[];
        foreach ($orders as $order){
            $orderIds[$order->id]=$order->total;
        }
        $order_goods=OrderGoods::find()->where(['in','order_id',array_keys($orderIds)])->all();
        //var_dump($order_goods);exit;

        return $this->renderPartial('my_order',['order_goods'=>$order_goods]);
    }
}