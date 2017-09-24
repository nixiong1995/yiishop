<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170913_060624_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username'=>$this->string()->notNull()->comment('用户名'),
            'auth_key'=>$this->string(32)->notNull()->comment('认证密钥'),
            'password_hash'=>$this->string()->notNull()->comment('密码'),
            'password_reset_token'=>$this->string()->comment('密码重置令牌'),
            'email'=>$this->string()->notNull()->comment('邮箱'),
            'status'=>$this->smallInteger(6)->notNull()->comment('状态'),
            'created_at'=>$this->integer()->notNull()->comment('创建时间'),
            'updated_at'=>$this->integer()->notNull()->comment('修改地址'),
            'last_login_time'=>$this->integer()->notNull()->comment('最后登录时间'),
            'last_login_ip'=>$this->string()->notNull()->comment('最后登录ip'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
