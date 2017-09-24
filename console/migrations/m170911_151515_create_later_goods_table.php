<?php

use yii\db\Migration;

/**
 * Handles the creation of table `later_goods`.
 */
class m170911_151515_create_later_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('goods', 'level',$this->integer());
    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('later_goods');
    }
}
