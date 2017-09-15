<?php
namespace backend\models;
use yii\db\ActiveRecord;

class ModifyForm extends ActiveRecord
{
    public $username;
    public $old_password;
    public $new_password;
    public $repeat_new_password;
    public $code;

    //验证规则
    public function rules()
    {
        return [
            [['old_password','new_password','repeat_new_password'],'required'],
            ['code','captcha','captchaAction'=>'modify-password/captcha'],
        ];
    }

    //字段中文命名
    public function attributeLabels()
    {
        return [
           'username'=>'用户名',
            'new_password'=>'新密码',
            'old_password'=>'旧密码',
            'repeat_new_password'=>'确认新密码',
            'code'=>'验证码',
        ];
    }

}