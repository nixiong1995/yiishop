<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Locations extends ActiveRecord{


    //验证规则
    public function rules()
    {
        return [
            [['name','phone','province','city','region','address'],'required'],
            ['phone','number'],
            ['default','safe'],
        ];
    }

}