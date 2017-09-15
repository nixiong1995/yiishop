<?php
namespace backend\controllers;
use backend\models\Admin;
use backend\models\ModifyForm;
use yii\web\Controller;

class ModifyPasswordController extends Controller
{
    public function actionIndex(){
        //根据identity信息判断用户是否登录,登录后才能修改密码
       if(\Yii::$app->user->identity){
           $model=new ModifyForm();
           $request=\Yii::$app->request;
           if($request->isPost){
               //模型加载数据
               $model->load($request->post());
               if($model->validate()){
                   //判断两次修改的密码是否输入一致
                   if($model->new_password===$model->repeat_new_password){
                       //根据用户登录的信息的id,查询出密码,与用户输入的旧密码进行对比
                       $admin=Admin::findOne(['id'=>\Yii::$app->user->id]);
                       //对比旧密码
                       $relust=\Yii::$app->security->validatePassword($model->old_password,$admin->password_hash);
                       if($relust){
                           //用户输入的旧密码和数据库中的密码对比成功
                           $admin->password_hash=\Yii::$app->security->generatePasswordHash($model->new_password);
                           $admin->save();
                           \Yii::$app->session->setFlash('success','修改密码成功');
                           return $this->redirect(['admin/index']);
                       }else{
                           //用户输入的旧密码和数据库中的密码对比失败
                           $model->addError('old_password','旧密码错误');
                       }
                   }else{
                       //新密码和旧密码不一致
                       $model->addError('repeat_new_password','两次密码不一样');
                   }
               }
            }
           return $this->render('index',['model'=>$model]);
        }else{
           //没有登录,跳转到登录页面.展示提示信息
           \Yii::$app->session->setFlash('error','请登录后操作');
           return $this->redirect(['admin/login']);
       }

    }

    //验证码
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                //设置验证码参数
                'minLength' => 4,
                'maxLength' => 4,
            ],
        ];
    }
}