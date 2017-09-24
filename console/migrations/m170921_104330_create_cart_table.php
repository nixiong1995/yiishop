<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170921_104330_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {/*goods_id	int	商品id
        amount	int	商品数量
        member_id	int	用户id*/
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            'amount'=>$this->integer()->notNull()->comment('商品数量'),
            'member_id'=>$this->integer()->notNull()->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');

    }
}
