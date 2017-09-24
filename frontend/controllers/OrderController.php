<?php
namespace frontend\controllers;
use backend\models\Goods;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{
    //订单列表
    public function actionList(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
            $member_id=\Yii::$app->user->id;//当前用户登录id
            //判断当前用户有没有填写收货地址
            $relust=Locations::findOne(['member_id'=>$member_id]);
            if(!$relust){
                //没有收货地址
                return $this->redirect(['locations/address']);
            }
            $addreies=Locations::find()->where(['member_id'=>$member_id])->all();//查询地址
            $carties=Cart::find()->where(['member_id'=>$member_id])->all();//查询购物车
            $cart=[];
            //遍历出购物车的商品id;再根据商品id查询商品详细信息
            foreach ($carties as $carts){
                $cart[$carts->goods_id]=$carts->amount;
            }
            $goods=Goods::find()->where(['in','id',array_keys($cart)])->all();//查询商品详细信息
            return $this->renderPartial('list',['addreies'=>$addreies,'goods'=>$goods,'cart'=>$cart]);
        }
    }


    //订单生成
    public function actionGenerate(){
        $model=new Order();
        $request=\Yii::$app->request;
        //var_dump($request->post());exit;
        $member_id=\Yii::$app->user->id;//当前登录用户id
        $address_id=$request->post('address_id','');//收货地址id
        $delivery_id=$request->post('deliveries');//送货方式id
        $payment_id=$request->post('pay');//支付方式id
        $money=$request->post('totalMoney');//商品金额
        //根据收货地址id查询收货地址
        $address=Locations::findOne(['id'=>$address_id,'member_id'=>$member_id]);
        //保存数据(赋值)
        $model->member_id=$member_id;
        $model->name=$address->name;
        $model->province=$address->province;
        $model->city=$address->city;
        $model->area=$address->region;
        $model->address=$address->address;
        $model->tel=$address->phone;
        $model->delivery_id=$delivery_id;
        $model->delivery_name=Order::$delivery[$delivery_id][0];
        $model->delivery_price=Order::$delivery[$delivery_id][1];
        $model->payment_id=$payment_id;
        $model->payment_name=Order::$payment[$payment_id][0];
        $model->total=$money+intval(Order::$delivery[$delivery_id][1]);
        if ($model->validate()){
            $model->status=1;
            $model->create_time=time();
            //var_dump($model);exit;
            $transaction=\Yii::$app->db->beginTransaction();//开启事务
            try{
                $model->save();
                //=========商品订单详细表=============
                $carts=Cart::find()->where(['member_id'=>$member_id])->all();//购物车表
                foreach ($carts as  $cart){
                    //判断商品库存是否充足
                    if($cart->amount>$cart->goods->stock){
                        throw new Exception($cart->goods->name.'商品库存不足,禁止下单');

                    }
                    $order_goods=new OrderGoods();
                    $order_goods->order_id=$model->id;//订单id
                    $order_goods->goods_id=$cart->goods_id;//商品id
                    $order_goods->goods_name=$cart->goods->name;
                    $order_goods->logo=$cart->goods->logo;
                    $order_goods->price=$cart->goods->shop_price;
                    $order_goods->amount=$cart->amount;
                    $order_goods->total=$model->total;
                    $order_goods->save();
                    $goods=Goods::findOne(['id'=>$order_goods->goods_id]);
                    //var_dump($goods);exit;
                    $goods->stock=$goods->stock-$order_goods->amount;
                    //var_dump($goods->stock);exit;
                    $goods->save();
                }
                $transaction->commit();
                Cart::deleteAll(['member_id'=>$member_id]);
                return $this->redirect(['order/prompt']);


            }catch ( Exception $e){
                //事务回滚
                $transaction->rollBack();
            }
        }
    }

    //下单成功提示页面
    public function actionPrompt(){
        return $this->renderPartial('index');
    }

}