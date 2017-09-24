<?php

use yii\db\Migration;

/**
 * Handles the creation of table `locations`.
 */
class m170920_113600_create_locations_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('locations', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull()->comment('用户id'),
            'name'=>$this->string()->notNull()->comment('收货人'),
            'province'=>$this->string(20)->notNull()->comment('省份'),
            'city'=>$this->string(20)->notNull()->comment('市'),
            'region'=>$this->string(20)->notNull()->comment('区'),
            'address'=>$this->string()->notNull()->comment('详细地址'),
            'default'=>$this->smallInteger()->comment('默认地址'),
            'phone'=>$this->string()->notNull()->comment('手机号码'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('locations');
    }
}
