<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public static $delivery=[
        1=>['普通快递','10.00','预计3-7日到达'],
        2=>['特快专递','30.00','预计1-3日到达'],
        3=>['加急专递','40.00','一线城市隔日到达'],
        4=>['平邮','10.00','预计3-7日到达']
    ];
    public static $payment=[
        1=>['货到付款','送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        2=>['在线支付','即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        3=>['上门自提','自提时付款，支持现金、POS刷卡、支票支付'],
        4=>['邮局汇款','通过快钱平台收款 汇款后1-3个工作日到账']
    ];

    //验证规则
    public function rules()
    {
        return [
           [
               ['member_id','name','province','city','area','address','tel',
               'delivery_id','delivery_name','delivery_price','payment_id',
               'payment_name','total'
               ],'required'
           ],
            [['tel','delivery_price','payment_id','total'],'number'],
            [['delivery','payment'],'safe'],
        ];
    }


}