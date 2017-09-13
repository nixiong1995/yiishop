<?php
namespace backend\models;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\ArrayHelper;


class Goods extends ActiveRecord
{
    //验证规则
    public function rules()
    {
        return [
            [['name','goods_category_id','brand_id','market_price','shop_price',
                'stock','is_on_sale','status','sort','logo'],'required'],
            [['market_price','shop_price'],'number'],
            [['stock','sort'],'integer'],
            //['level','required'],
            [['level'],'required','requiredValue'=>'3','message'=>'只能选择3级分类']
        ];
    }

    //字段中文命名
    public function attributeLabels()
    {
        return [
            'name'=>'商品名称',
            'logo'=>'图片',
            'goods_category_id'=>'商品分类',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在售',
            'status'=>'状态',
            'sort'=>'排序',
            'level'=>' ',
        ];
    }


    //查询当天添加商品次数
    public static function getDayCount(){
        $time=time();//当前时间戳
        //当前时间年月日格式(查询时间)
        $queryTime=date("Y-m-d",$time);
        //var_dump($queryTime);exit;
        $relust=GoodsDayCount::find()->where(['day'=>$queryTime])->one();//根据当前时间查询的结果
        //判断当天是否已经添加过商品
        if($relust){
            //当天已添加商品
            $relust->count=$relust->count+1;
            $relust->save(false);
            return $relust->count+(date("Ymd",$time)*10000);
        }else{
            //当天未添加商品
            $model=new GoodsDayCount();
            $model->day=date("Ymd",$time);;
            $model->count=1;
            $model->save(false);
            return $model->count+(date("Ymd",$time)*10000);
        }
    }




}