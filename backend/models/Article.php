<?php
namespace backend\models;
use app\models\ArticleCategory;
use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public $content;
    //验证规则
    public function rules()
    {
        return [
            [['name','intro','article_category_id','sort','status'],'required'],
            [['sort', 'status'],'integer'],
        ];
    }

    //一定字段中文名
    public function attributeLabels()
    {
        return [
            'name'=>'文章标题',
            'intro'=>'文章简介',
            'article_category_id'=>'所属分类',
            'sort'=>'排序',
            'status'=>'状态',
        ];
    }

    //获取文章分类
    public function getCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }

}