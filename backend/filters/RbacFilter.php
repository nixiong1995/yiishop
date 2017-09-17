<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class RbacFilter extends ActionFilter
{
    public function beforeAction($action)
    {
       // return \Yii::$app->user->can($action->getUniqueId());//检查是否是当前路由的权限
        //return false;
        //判断是否有权
        if(!\Yii::$app->user->can($action->uniqueId)){
            //判断如果用户没有登录,跳转到登录页面
            if(\Yii::$app->user->isGuest){
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            //没有权限,显示提示信息
            throw new ForbiddenHttpException('对不起,你没有改操作权限');
        }
        return parent::beforeAction($action);
    }
}