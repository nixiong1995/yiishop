<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord{

    //验证规则
    public function rules()
    {
        return [
            [['goods_id','amount'],'required']
        ];
    }

}