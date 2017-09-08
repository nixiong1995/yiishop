<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Brand extends ActiveRecord
{
    public $code;

    //验证规则
    public function rules()
    {
        return [
            [['name','intro','sort','status','logo'],'required'],
            ['code','captcha','captchaAction'=>'brand/captcha']
        ];
    }

    //定义字段中文名
    public function attributeLabels()
    {
        return [
            'name'=>'品牌名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
            'logo'=>'logo图片上传',
            'code'=>'验证码',
        ];
    }
}