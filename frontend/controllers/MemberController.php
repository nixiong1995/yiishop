<?php
namespace frontend\controllers;
use Codeception\Module\Redis;
use frontend\models\Member;
use frontend\models\SmsDemo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MemberController extends Controller
{
    //注册
    public function actionRegister(){
        $model=new Member();
        $model->scenario=Member::SCENARIO_REGIST;
        $requset=\Yii::$app->request;
        if($requset->isPost){
            $formSms=$requset->post('sms');
           $model->load($requset->post(),'');
            if($model->validate()){
                $redis=new \Redis();
                $redis->connect('127.0.0.1');
                $phone=$redis->get('tel');
                $sms=$redis->get('captcha');
                //var_dump($model->tel);exit;
                if($formSms!=$sms || $model->tel!=$phone){
                    throw new NotFoundHttpException('手机号或者验证码有误,请重新核定后注册');
                }
                $model->save(false);
                $this->redirect(['member/login']);

            }
        }
        return $this->renderPartial('register');
/*var_dump(\Yii::$app->user->isGuest);
var_dump(\Yii::$app->user->identity);
var_dump(\Yii::$app->user->id);*/
    }

    //登录
    public function actionLogin(){
        $model=new Member();
        $requset=\Yii::$app->request;
        //var_dump($requset->post());exit;
        /*$username=$requset->post('username');
        $model=Member::findOne(['username'=>$username]);*/
        if($requset->isPost){
            $model->load($requset->post(),'');
            //var_dump($model);exit;
            if($model->validate()){
                //var_dump($model);exit;
                $user=Member::findOne(['username'=>$model->username]);
                //var_dump($user);exit;
                if($user){
                    $relust=\Yii::$app->security->validatePassword($model->password,$user->password_hash);//验证密码
                    if($relust){
                        //密码正确
                        $user->last_login_time=time();
                        $user->last_login_ip=\Yii::$app->request->userIP;
                        $user->save(false);
                        //判断用户是否勾选记住登录信息
                        if($model->remember){
                            //echo '自动登录成功';exit;
                            \Yii::$app->user->login($user,7*24*3600);
                        }else{
                            //echo '登录成功';exit;
                           \Yii::$app->user->login($user);
                           Member::synchroniza();
                           //\Yii::$app->session->setFlash('success','登录成功');
                        }

                        return $this->redirect(['shop/index']);
                    }else{
                        //密码错误
                        throw new NotFoundHttpException('密码错误');
                    }
                }else{
                    //没有查到该用户
                    //var_dump(111);exit;
                    //echo '没有该用户';exit;
                    throw new NotFoundHttpException('没有该用户');
                }
            }
        }
        return $this->renderPartial('login');
    }

    //验证用户名唯一性
    public function actionValidateUser($username){
        $relust=Member::findOne(['username'=>$username]);
        if($relust){
            return 'false';
        }else{
            return 'true';
        }
    }

    //验证邮箱唯一性
    public function actionValidateEmail($email){
        $relust=Member::findOne(['email'=>$email]);
        if($relust){
            return 'false';
        }else{
            return 'true';
        }
    }

    //验证手机号唯一性
    public function actionValidateTel($tel){
        $relust=Member::findOne(['tel'=>$tel]);
        if($relust){
            return 'false';
        }else{
            return 'true';
        }
    }


    //发送手机短信
    public function actionSms($tel){
        $captcha=rand(100000,999999);
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set("tel","$tel");
        $redis->set("captcha","$captcha");
        $demo = new SmsDemo(
            "LTAIblu8cPZ3ZQjj",
            "60pn0FHB5M9sv4Q1Aya8gzpJTJZ20u"
        );
        echo "SmsDemo::sendSms\n";
        $response = $demo->sendSms(
            "yiishop购物商城", // 短信签名
            "SMS_97935005", // 短信模板编号
            "$tel", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$captcha,
                //"product"=>"dsd"
            )
        );
        $data=['tel'=>$tel,'captcha'=>$captcha,'success'=>'验证码发送成功'];
       if($response->Message=='OK'){
           return json_encode($data);
       }else{
           return '验证码发送失败';
       }
        //print_r($response->Message);
    }


    //验证手机短信
    public function actionValidateSms($phone,$sms){
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $code=$redis->get('captcha');
        $tel=$redis->get('tel');
        if($code==$sms && $tel==$phone){
            return 'true';
        }
        return 'false';

    }

}