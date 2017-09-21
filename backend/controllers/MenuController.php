<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\web\Controller;

class MenuController extends Controller
{
    //菜单列表
    public function actionIndex(){
        $models=Menu::find()->all();
        return $this->render('index',['models'=>$models]);
    }

    //菜单添加
    public function actionAdd(){
        $model=new Menu();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['menu/add']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //验证访问权限
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['login','logout','captcha','error'],
            ]
        ];
    }

}