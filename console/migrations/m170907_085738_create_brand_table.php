<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170907_085738_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'logo'=>$this->string()->notNull()->comment('logo图片'),
            'sort'=>$this->integer(11)->notNull()->comment('排序'),
            'status'=>$this->smallInteger(2)->notNull()->comment('状态')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}