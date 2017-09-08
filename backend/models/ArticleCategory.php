<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    public $code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'sort', 'status'], 'required'],
            [['intro'], 'string','max' => 50],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' =>25],
            ['code','captcha','captchaAction'=>'article-category/captcha']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章名称',
            'intro' => '文章简介',
            'sort' => '排序',
            'status' => '状态',
            'code'=>'验证码'
        ];
    }
}
