<?php
namespace backend\controllers;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;

class GoodsCategoryController extends Controller
{
    //商品分类分页列表
    public function actionIndex(){
        //查询分页数据
        $query=GoodsCategory::find();
        //实现分页类
        $pager=new Pagination([
            'totalCount'=>$query->count(),//总条数
            'defaultPageSize'=>10//每页显示条数
        ]);
        //分页查询
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        //调用视图展示数据
        return $this->render('index',['models'=> $models,'pager'=>$pager]);
    }

    //添加商品分类
    public function actionAdd(){
        $model=new GoodsCategory();
        $request=\Yii::$app->request;
        if($request->isPost){
            //模型加载数据
            $model->load($request->post());
            if($model->validate()){
                //判断是添加顶级分类还非顶级分类
                if($model->parent_id){
                    //非顶级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                   //顶级分类
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }


    //修改商品分类
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        //$update_parentId=$model->parent_id;//修改数据的parent_id
        //var_dump( $update_parentId);exit;
        $request=\Yii::$app->request;
        if($request->isPost){
            //模型加载数据
            $model->load($request->post());
            //var_dump($model);exit;
            if($model->validate()){
                //var_dump(111);exit;
                //判断是添加顶级分类还非顶级分类
                if($model->parent_id){

                    //非顶级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    //判断如果本身parent_id是0,要修改的也是顶级分类的话就说明只是更改分类名称和简介,只需要保存即可
                    if($model->getAttributeLabel('parent_id')==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }
                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }


    //删除商品分类
    public function actionDel(){
        //接收id
        $id=\Yii::$app->request->post('id');
        //根据id查询数据表是否有parent_id等于此id的数据,如果有说明子分类就不允许删除
       $result=GoodsCategory::findOne(['id'=>$id]);
        //var_dump($result);exit;
       /* if($result){
            return 'prohibit';
        }
        //根据id查询出这条数据,执行删除
        $model=GoodsCategory::findOne(['id'=>$id])->delete();
        if($model){
            return 'success';
        }else{
            return 'fail';
        }*/
       if($result->isLeaf()){//是否是叶子节点(是否有子节点)
           $result->deleteWithChildren();//删除当前节点和子孙节点
           return 'success';
       }else{
           return 'prohibit';
        }
        return 'fail';

    }
}