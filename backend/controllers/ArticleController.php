<?php
namespace backend\controllers;
use app\models\ArticleCategory;
use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends Controller
{
    public function actionIndex(){
        //查询所有数据
        $query=Article::find();
        //实现分页类
        $pager=new Pagination([
            'totalCount'=>$query->where(['>','status',-1])->count(),//总条数
            'defaultPageSize'=>5//每页显示条数
        ]);
        //分页查询
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        //调用视图展示数据
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    //添加文章
    public function actionAdd(){
        $model1=new Article();//文章模型
        $model2=new ArticleDetail();//文章详情模型
        $request=\Yii::$app->request;//接收方式模型
        if($request->isPost){
            //模型加载数据
            $model1->load($request->post());
            $model2->load($request->post());
            if($model1->validate() && $model2->validate()){
                $model1->create_time =time();
                $model1->save();
                $model2->article_id=$model1->id;
                $model2->save();
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article/index']);
            }
        }
        $article_categorys=ArticleCategory::find()->all();
        $article_category=[];
        foreach ($article_categorys as $v){
            $article_category[$v->id]=$v->name;
        }
        //调用视图展示添加文章表单
        return $this->render('add',['model1'=>$model1,'model2'=>$model2,'article_category'=>$article_category]);
    }

    //修改文章
    public function actionEdit($id){
        //根据id查询到修改的数据进行修改
        $model1=Article::findOne(['id'=>$id]);//文章基本信息表
        $model2=ArticleDetail::findOne(['article_id'=>$id]);//文章详细信息本
        $request=\Yii::$app->request;//接收方式模型
        if($request->isPost){
            //模型加载数据
            $model1->load($request->post());
            $model2->load($request->post());
            if($model1->validate() && $model2->validate()){
                $model1->save();
                $model2->save();
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article/index']);
            }
        }
        $article_categorys=ArticleCategory::find()->all();
        $article_category=[];
        foreach ($article_categorys as $v){
            $article_category[$v->id]=$v->name;
        }
        //调用视图展示添加文章表单
        return $this->render('add',['model1'=>$model1,'model2'=>$model2,'article_category'=>$article_category]);
    }

    //删除文章
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        $model=Article::find()->where(['id'=>$id])->one();
        if($model){
            $model->status=-1;
            $model->save(false);
            return 'success';
        }
        return 'fail';
    }

    //UEditor插件
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],
            'config' => [
                "imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                "imageRoot" => \Yii::getAlias("@webroot"),
            ]
        ];
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