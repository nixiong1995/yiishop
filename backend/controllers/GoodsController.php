<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\Controller;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class GoodsController extends Controller
{
    //查询所有数据,完成分页
    public function actionIndex(){
        $name=\Yii::$app->request->get('name');
        $sn=\Yii::$app->request->get('sn');
        $price_begin=\Yii::$app->request->get('price_begin');
        $price_end=\Yii::$app->request->get('price_end');
         $query=Goods::find()->where(['status'=>1]);
         if($name){
             $query->andWhere(['like','name',$name]);
         }elseif ($sn){
             $query->andWhere(['like','sn',$sn]);
         }elseif ($price_begin){
             $query->andWhere(['>=','shop_price',$price_begin]);
         }elseif ($price_end){
             $query->andWhere(['<=','shop_price',$price_end]);
         }else{
             $query->all();
         }

          //实例化分页类
          $pager=new Pagination([
              'totalCount'=>$query->count(),//总条数
              'defaultPageSize'=>5,//每页显示条数
          ]);
          //
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
          //调用视图展示数据
          return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

      //添加商品
    public function actionAdd(){
        $model1=new Goods();//商品模型
        $model2=new GoodsIntro();//商品描述模型
        $request=\Yii::$app->request;
        if($request->isPost){
            //模型加载数据
            $model1->load($request->post());
            $model2->load($request->post());
            if($model1->validate() && $model2->validate()){
                $count=Goods::getDayCount();//查询商品添加次数模型
                //var_dump($count);exit;
                $model1->sn=$count;
                $model1->create_time=time();//添加时间
                $model1->save();//商品模型保存数据
                $goods_id=$model1->id;
                $model2->goods_id=$goods_id;
                $model2->save();//商品描述模型保存数据
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }

        }
        //查询品牌分类数据表,展示到添加下拉框中
        $brand=Brand::find()->select(['id','name'])->all();
        $brandCategory=[];
        foreach ($brand as $value){
            $brandCategory[$value->id]=$value->name;
        }
        return $this->render('add',['model1'=>$model1,'model2'=>$model2,'brandCategory'=>$brandCategory]);
    }

    //修改商品
    public function actionEdit($id){
        $model1=Goods::findOne(['id'=>$id]);//商品信息模型
        $model2=GoodsIntro::findOne(['goods_id'=>$id]);//商品详细信息模型
        $request=\Yii::$app->request;
        if($request->isPost){
            //模型加载数据
            $model1->load($request->post());
            $model2->load($request->post());
            if($model1->validate() && $model2->validate()){
                $model1->save();//商品模型保存数据
                $goods_id=$model1->id;
                $model2->goods_id=$goods_id;
                $model2->save();//商品描述模型保存数据
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods/index']);
            }

        }
        //查询品牌分类数据表,展示到添加下拉框中
        $brand=Brand::find()->select(['id','name'])->all();
        $brandCategory=[];
        foreach ($brand as $value){
            $brandCategory[$value->id]=$value->name;
        }
        return $this->render('add',['model1'=>$model1,'model2'=>$model2,'brandCategory'=>$brandCategory]);
    }

    //删除商品
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        $model=Goods::findOne(['id'=>$id]);
        if($model){
            $model->status=0;
            $model->save(false);
            return 'success';
        }else{
            return 'fail';
        }
    }

    //文件上传插件和七牛云
    public function actions(){
        return [
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
            //UEditor插件
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
                'except'=>['login','logout','captcha','error','s-upload','upload'],
            ]
        ];
    }




}