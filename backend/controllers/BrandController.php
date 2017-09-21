<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends Controller
{
    //查询所有数据及分页
    public function actionIndex()
    {
        //查询所有数据
        $query=Brand::find();
        //实现分页类
        $pager=new Pagination([
            'totalCount'=>$query->where(['>','status',-1])->count(),//总条数
            'defaultPageSize'=>5//每页显示条数
        ]);
        //分页查询
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        //调用视图展示数据
        return $this->render('index',['models'=> $models,'pager'=>$pager]);



    }

    //添加品牌
    public function actionAdd(){
        //创建一个brand对象
        $model=new Brand();
        //实例化request对象
        $request=\Yii::$app->request;
        if($request->isPost){
            //模型加载数据
            $model->load($request->post());
            //处理上传文件
            if($model->validate()){//验证规则
                //保存所有数据
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
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
            //文件上传插件
            's-upload' => [
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
                    //$action->output['fileUrl'] = $action->getWebUrl();
                    //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                   // $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                   // $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                   /* $config = [
                        'accessKey'=>'KmCFMg8--qsvMEut2FnrhEKZ9xh0UzIFht7pAu2I',
                        'secretKey'=>'GpSyOuo27mmjVAIBuiDwMS8-2v-ntq32OohTCH4p',
                        'domain'=>'http://ovyao1x76.bkt.clouddn.com/',
                        'bucket'=>'yiishop',
                        'area'=>Qiniu::AREA_HUADONG
                    ];*/
                    $qiniu = new Qiniu(\Yii::$app->params['qiniuyun']);
                    $key =$action->getWebUrl();
                    //删除文件到七牛云,同时指定路径
                    $file=$action->getSavePath();
                    $qiniu->uploadFile($file,$key);
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] = $url;//输出图片路径
                },
            ],
        ];
    }
    //修改品牌
    public function actionEdit($id){
        //根据id查询出要修改的数据
        $model=Brand::findOne(['id'=>$id]);
        //实例化request对象
        $request=\Yii::$app->request;
        if($request->isPost){
            //模型加载数据
            $model->load($request->post());
            if($model->validate()){//验证规则
                //保存所有数据
                $model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);

    }

    //品牌删除
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        //根据id查找数据并修改status状态值
       $brand=Brand::find()->where(['id'=>$id])->one();
       if($brand){
           $brand->status=-1;
           $brand->save(false);
          return 'success';
       }else{
           return 'fail';
       }

    }

    //验证访问权限
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['login','logout','captcha','error','s-upload','upload'],
            ]
        ];
    }




}