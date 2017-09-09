<?php
namespace backend\models;
use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord
{
    public function rules()
    {
        return [
            ['content','required']
        ];
    }


}