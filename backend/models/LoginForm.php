<?php
namespace backend\models;
use yii\bootstrap\ActiveForm;
use yii\db\ActiveRecord;

class LoginForm extends ActiveRecord
{
    public $code;
    public $username;
    public $password;
    public $remember ;


    public function rules()
    {
        return [
            ['code','captcha','captchaAction'=>'admin/captcha'],
            [['username','password'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
            'remember'=>'',
        ];
    }

    public function login(){
        //根据用户查询数据表
        $admin=Admin::findOne(['username'=>$this->username]);
        $admin->last_login_time=time();
        $admin->last_login_ip=\Yii::$app->request->userIP;
        $admin->save(false);
        if($admin){
            //查询到该用户,验证密码
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                //密码争取允许登录
                return \Yii::$app->user->login($admin);
            }else{
                //密码错误
                $this->addError('password','密码错误');
            }
        }else{
            //没有查到改用户
            $this->addError('username','没有改用户');
        }
        return false;
    }

}