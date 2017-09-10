<?php
namespace backend\models;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\ArrayHelper;

class GoodsCategory extends ActiveRecord
{

    public function rules()
    {
        return [
            [['name','intro','parent_id'],'required'],
            ['parent_id','integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'intro'=>'分类简介',
            'parent_id'=>'上级分类',
        ];
    }

    //获取商品分类的ztree数据
    public static function getZNodes(){
        $top=['id'=>0,'name'=>'顶级分类','parent_id'=>0];
        $goodsCategories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
       return ArrayHelper::merge([$top], $goodsCategories);
       //var_dump($goodsCategories);exit;
    }

    //商品无限级分类
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategroyQuery(get_called_class());
    }
}
