<?php
namespace frontend\controllers;
use frontend\models\Locations;
use yii\web\Controller;


class LocationsController extends Controller
{
    //新增地址
    public function actionAddress(){
        $model=new Locations();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                //var_dump($model);exit;
                $model->member_id=\Yii::$app->user->id;
                $model->save();
            }
        }
        //显示已有地址
        $id=\Yii::$app->user->id;
        $models=Locations::find()->where(['member_id'=>$id])->all();
        return $this->renderPartial('address',['models'=>$models]);
    }

    //修改地址
    public function actionEdit($id){
        $model=Locations::findOne(['id'=>$id]);
        $requset=\Yii::$app->request;
        if($requset->isPost){
            $model->load($requset->post(),'');
            if($model->validate()){
                $model->save();
                return $this->redirect(['address','id'=>$id]);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }

    //删除地址
    public function actionDel(){
        $id=\Yii::$app->request->get('id');
        $relust=Locations::findOne(['id'=>$id])->delete();
        if($relust){
            return 'success';
        }else{
            return 'fail';
        }

    }


}
