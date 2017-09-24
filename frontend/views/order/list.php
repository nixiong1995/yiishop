<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">

    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/cart2.js"></script>
<style type="text/css">
    .fillin_ft button{float: right;
        display: inline;
        width: 135px;
        height: 36px; background: url("../images/order_btn.jpg") 0 0 no-repeat;
        vertical-align: middle; margin: 7px 10px 0;
    }
</style>
</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！[<a href="login.html">登录</a>] [<a href="register.html">免费注册</a>] </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
<form action="<?=\yii\helpers\Url::to(['order/generate'])?>" method="post">
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($addreies as $address)?>
                <p>
                    <input type="radio" value="<?=$address->id?>" name="address_id"/><?=$address->name?>&nbsp;<?=$address->phone?>&nbsp;
                    <?=$address->province?>&nbsp; <?=$address->city?>&nbsp;  <?=$address->region?>&nbsp;<?=$address->address?>&nbsp;
                </p>
                <?php ?>



        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">送货速度</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="cur">
                        <td data-price="<?=\frontend\models\Order::$delivery[1][1]?>">
                            <input type="radio" name="deliveries" checked="checked" value="1"/><?=\frontend\models\Order::$delivery[1][0]?>

                        </td>
                        <td><?=\frontend\models\Order::$delivery[1][1]?></td>
                        <td><?=\frontend\models\Order::$delivery[1][2]?></td>
                    </tr>
                    <tr>

                        <td data-price="<?=\frontend\models\Order::$delivery[2][1]?>"><input type="radio" name="deliveries" value="2"/><?=\frontend\models\Order::$delivery[2][0]?></td>
                        <td><?=\frontend\models\Order::$delivery[2][1]?></td>
                        <td><?=\frontend\models\Order::$delivery[2][2]?></td>
                    </tr>
                    <tr>

                        <td data-price="<?=\frontend\models\Order::$delivery[3][1]?>"><input type="radio" name="deliveries" value="3"/><?=\frontend\models\Order::$delivery[3][0]?></td>
                        <td><?=\frontend\models\Order::$delivery[3][1]?></td>
                        <td><?=\frontend\models\Order::$delivery[3][2]?></td>
                    </tr>
                    <tr>

                        <td data-price="<?=\frontend\models\Order::$delivery[4][1]?>"><input type="radio" name="deliveries" value="4"  /><?=\frontend\models\Order::$delivery[4][0]?></td>
                        <td><?=\frontend\models\Order::$delivery[4][1]?></td>
                        <td><?=\frontend\models\Order::$delivery[4][2]?></td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <tr class="cur">
                        <td class="col1"><input type="radio" name="pay" value="1"/><?=\frontend\models\Order::$payment[1][0]?></td>
                        <td class="col2"><?=\frontend\models\Order::$payment[1][1]?></td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="2"/><?=\frontend\models\Order::$payment[2][0]?></td>
                        <td class="col2"><?=\frontend\models\Order::$payment[2][1]?></td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="3" /><?=\frontend\models\Order::$payment[3][0]?></td>
                        <td class="col2"><?=\frontend\models\Order::$payment[3][1]?></td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="4"/><?=\frontend\models\Order::$payment[4][0]?></td>
                        <td class="col2"><?=\frontend\models\Order::$payment[4][1]?></td>
                    </tr>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
       <!-- <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>-->
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $totalCount=0; $totalMoney=0;foreach ($goods as $good):?>
                <tr>
                    <td class="col1"><a href=""><img src="<?=$good->logo?>" alt="" /></a>  <strong><a href=""><?=$good->name?></a></strong></td>
                    <td class="col3">￥<?=$good->shop_price?></td>
                    <td class="col4"> <?=$cart[$good->id]?></td>
                    <td class="col5"><span>￥<?=$cart[$good->id]*$good->shop_price?>.00</span></td>
                    <?php
                        $totalCount+=$cart[$good->id];
                        $totalMoney+=$cart[$good->id]*$good->shop_price;
                    ?>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$totalCount?> 件商品，总商品金额：</span>
                                <em class="totalMoney1">￥<?=$totalMoney?>.00</em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥0.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em class="freight" data-tmoney="<?=$totalMoney?>">￥10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em class="totalMoney">￥<?=$totalMoney+10?>.00</em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <button type="submit" value=""></button>
        <p>应付总额：<strong class="totalMoney">￥<?=$totalMoney+10?>.00元</strong></p>

    </div>
</div>
    <input type="hidden" name="totalMoney" value="<?=$totalMoney?>"/>
    <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>"/>
</form>
<!-- 主体部分 end -->
    <script type="text/javascript">
        $(function () {
            $('.table input').on('click',function () {
                var deliverPrice=$(this).closest('td').attr('data-price');
                $('.freight').text('￥'+deliverPrice);
                var totalMoney=$('.freight').attr('data-tmoney');
                var totalMoneys=Number(totalMoney)+Number(deliverPrice);
                console.debug(totalMoneys)
                $('.totalMoney').text('￥'+totalMoneys+'.00')
            });
        });

    </script>

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
</body>
</html>
