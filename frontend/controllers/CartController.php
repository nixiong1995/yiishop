<?php
namespace frontend\controllers;
use backend\models\Goods;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Cookie;

class CartController extends Controller{
    public $enableCsrfValidation=false;
    //添加到购物车页面
    public function actionAddtocarts($goods_id,$amount){
        //判断用户是否登录
        if(\Yii::$app->user->isGuest){
            //未登录将商品信息存入cookie
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('carts');
            if($value){
                $carts=unserialize($value);
            }else{
                $carts=[];
            }
            //检查购物车中是否存在当前需要添加的商品
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id]+=$amount;
            }else{
                $carts[$goods_id]=$amount;
            }
            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            $cookie->name='carts';
            $cookie->value=serialize($carts);
            $cookie->expire=time()+7*24*3600;//过期时间戳
            $cookies->add($cookie);
        }else{
            //登录将商品信息存在数据表
            //判断数据表中是否已有当前商品
            $user_id=\Yii::$app->user->id;
            $model=Cart::find()->where(['member_id'=>$user_id])->andWhere(['goods_id'=>$goods_id])->one();
            if($model){
                $model->amount=$model->amount+$amount;
                $model->save();
            }else{
                $model=new Cart();
                $request=\Yii::$app->request;
                //模型修改数据
                //var_dump($request->get());exit;
                $model->load($request->get(),'');
                if($model->validate()){
                    $model->member_id=\Yii::$app->user->id;
                    $model->save();
                 }
            }
        }
        //跳转到购物车
        return $this->redirect(['cart/shop-carts']);

    }

    //购物车页面
    public function actionShopCarts(){
        //获取购物车数据
        if(\Yii::$app->user->isGuest){
            //未登录从cookie中取数据
            $cookies=\Yii::$app->request->cookies;
            $carts=unserialize($cookies->getValue('carts'));
            $models=Goods::find()->where(['in','id',array_keys($carts)])->all();
        }else{
            //已登录
            $user_id=\Yii::$app->user->id;
            //var_dump($user_id);exit;
            $cartes=Cart::find()->where(['member_id'=>$user_id])->all();
            //var_dump($carts);exit;
            $carts=[];
            foreach ($cartes as $cart){
                $carts[$cart->goods_id]=$cart->amount;
            }
            //var_dump($goods);exit;
            $models=Goods::find()->where(['in','id',array_keys($carts)])->all();
        }
        return $this->renderPartial('cart',['models'=>$models,'carts'=>$carts]);
    }

    //通过AJAX修改商品数量
    public function actionAjax(){

        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('carts');
            if($value){
                $carts=unserialize($value);
            }else{
                $carts=[];
            }
            //检查购物车中是否存在当前需要添加的商品
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id]=$amount;
            }
            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            $cookie->name='carts';
            $cookie->value=serialize($carts);
            $cookie->expire=time()+7*24*3600;//过期时间戳
            $cookies->add($cookie);
        }else{
            $member_id=\Yii::$app->user->id;
            $model=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            if($model){
                $model->amount=$amount;
                $model->save();
            }
        }
    }

    //删除购物车
    public function actionDel(){
        $id=\Yii::$app->request->get('id');//传过来的商品id
        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('carts');
            if($value){
                $carts=unserialize($value);
                unset($carts[$id]);
            }
            //var_dump($carts);exit;
            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            $cookie->name='carts';
            $cookie->value=serialize($carts);
            $cookie->expire=time()+7*24*3600;//过期时间戳
            $cookies->add($cookie);
            return 'success';
        }else{
            $member_id=\Yii::$app->user->id;//用户id
            $model=Cart::findOne(['goods_id'=>$id,'member_id'=>$member_id])->delete();
            if($model){
                return 'success';
            }
        }
    }
}