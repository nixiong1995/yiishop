<?php

namespace backend\controllers;

use app\models\ArticleCategory;
use yii\data\Pagination;


class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //查询所有数据
        $query=ArticleCategory::find();
        //实现分页类
        $pager=new Pagination([
            'totalCount'=>$query->where(['status'=>1])->count(),//总条数
            'defaultPageSize'=>5//每页显示条数
        ]);
        //分页查询
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        //调用视图展示数据
        return $this->render('index',['models'=> $models,'pager'=>$pager]);
    }

    //添加文章

    /**
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        //创建一个brand对象
        $model=new ArticleCategory();
        //实例化request对象
        $request=\Yii::$app->request;
        if($request->isPost){
            //模型加载数据
            $model->load($request->post());
            //var_dump($model);exit;
            if($model->validate()){//验证规则
                //var_dump($model);exit;
                //保存所有数据
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //修改文章
    public function actionEdit($id){
        //根据id查询出这条数据,进行修改
        $model=ArticleCategory::findOne(['id'=>$id]);
        //实例化request对象
        $request=\Yii::$app->request;
        if($request->isPost){
            //模型加载数据
            $model->load($request->post());
            //var_dump($model);exit;
            if($model->validate()){//验证规则
                //var_dump($model);exit;
                //保存所有数据
                $model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //删除文章
    public function actionDel($id){
        //根据id查找数据并修改status状态值
        $brand=ArticleCategory::find()->where(['id'=>$id])->one();
         $brand->status=-1;
         $brand->save(false);
        //跳转
        return $this->redirect(['article-category/index']);
    }

    //验证码
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                //设置验证码参数
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }


}
