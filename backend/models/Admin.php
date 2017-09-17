<?php
namespace backend\models;
use phpDocumentor\Reflection\Location;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;

class Admin extends ActiveRecord implements IdentityInterface
{
    public $password;//未加密的密码
    public $roles;
    //常量定义场景
    const SCENARIO_Add ='add';
    const SCENARIO_EDIT ='edit';
    //验证规则
    public function rules()
    {
        return [
            [['username','email','status'],'required'],
            ['email','email'],
            ['password','required','on'=>self::SCENARIO_Add],//指定场景,该规则只在指定场景下生效
            [['username','email'],'unique'],
            ['password','string'],
            ['roles','safe'],
        ];
    }

    //字段中文命名
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'email'=>'邮箱',
            'status'=>'状态',
            'roles'=>'角色',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            //添加管理员
            $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password);//对提交过来的加密
            $this->created_at=time();//生成添加时间
            $this->auth_key=\Yii::$app->security->generateRandomString();//生成自动登录密钥(随机字符串)
        }else{
            //修改管理员
            if($this->password){
                //如果没有填写修改密码,就不修改密码
                $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password);//对提交过来的加密
            }
            $this->updated_at=time();//生成添加时间
            $this->auth_key=\Yii::$app->security->generateRandomString();//生成自动登录密钥(随机字符串)

        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    //获取角色选项
    public static function getRoles(){
        $roles=\Yii::$app->authManager->getRoles();
        $Items=[];
        foreach ( $roles as $role){
            $Items[$role->name]=$role->description;
        }
        return $Items;
    }


    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
       return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
       return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $authKey==$this->auth_key;
    }
}