<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\GoodsGallery;
use yii\web\Controller;
use flyok666\uploadifive\UploadAction;

class GoodsGalleryController extends Controller
{
    public function actionIndex($id){
        $models=GoodsGallery::find()->where(['goods_id'=>$id])->all();

            return $this->render('index',['models'=>$models,'id'=>$id]);



    }

    //商品图片的添加
    public function actionAdd(){
        $id=\Yii::$app->request->post('id');
        $path=\Yii::$app->request->post('path');
        $model=new GoodsGallery();
        $model->goods_id=$id;
        $model->path=$path;
        if($model->save(false)){
            return 'success';
        }else{
            return 'fail';
        }

    }

    //商品图片删除
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        $model=GoodsGallery::findOne(['id'=>$id])->delete();
        if($model){
            return 'success';
        }
        return 'fail';
    }


    public function actions(){
       return [
           's-upload'=> [
               'class' => UploadAction::className(),
               'basePath' => '@webroot/upload',
               'baseUrl' => '@web/upload',
               'enableCsrf' => true, // default
               'postFieldName' => 'Filedata', // default
               //BEGIN METHOD
               'format' => [$this, 'methodName'],
               //END METHOD
               //BEGIN CLOSURE BY-HASH
               'overwriteIfExist' => true,
               'format' => function (UploadAction $action) {
                   $fileext = $action->uploadfile->getExtension();
                   $filename = sha1_file($action->uploadfile->tempName);
                   return "{$filename}.{$fileext}";
               },
               //END CLOSURE BY-HASH
               //BEGIN CLOSURE BY TIME
               'format' => function (UploadAction $action) {
                   $fileext = $action->uploadfile->getExtension();
                   $filehash = sha1(uniqid() . time());
                   $p1 = substr($filehash, 0, 2);
                   $p2 = substr($filehash, 2, 2);
                   return "{$p1}/{$p2}/{$filehash}.{$fileext}";
               },
               //END CLOSURE BY TIME
               'validateOptions' => [
                   'extensions' => ['jpg', 'png'],
                   'maxSize' => 1 * 1024 * 1024, //file size
               ],
               'beforeValidate' => function (UploadAction $action) {
                   //throw new Exception('test error');
               },
               'afterValidate' => function (UploadAction $action) {},
               'beforeSave' => function (UploadAction $action) {},
               'afterSave' => function (UploadAction $action) {
                   $action->output['fileUrl'] = $action->getWebUrl();
                   //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                   // $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                   // $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
               }
               ]
       ];
    }

    //验证访问权限
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['login','logout','captcha','error','s-upload'],
            ]
        ];
    }
}
