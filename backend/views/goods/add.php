<?php
use yii\web\JsExpression;
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model1,'name')->textInput();
echo $form->field($model1,'logo')->hiddenInput();
/////////////////uploadifive插件//////////////////
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将文件路径写入logo字段隐藏域
        $("#goods-logo").val(data.fileUrl);
        //图片回显
        $("#img").attr("src",data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo yii\bootstrap\Html::img($model1->logo,['class'=>'img-cricle','style'=>'width:150px','id'=>'img','padding-top'=>10]);
////////////////////////////////////////////////////
echo $form->field($model1,'goods_category_id')->hiddenInput();
echo '<div><ul id="treeDemo" class="ztree"></ul></div>';
echo $form->field($model1,'level')->hiddenInput();
echo $form->field($model1,'brand_id')->dropDownList($brandCategory);
echo $form->field($model1,'market_price')->textInput();
echo $form->field($model1,'shop_price')->textInput();
echo $form->field($model1,'stock')->textInput();
echo $form->field($model1,'is_on_sale',['inline'=>true])->radioList(['下架','在售']);
echo $form->field($model1,'status',['inline'=>true])->radioList(['回收站','正常']);
echo $form->field($model1,'sort')->textInput();
echo $form->field($model2,'content')->widget('kucha\ueditor\UEditor');
echo '<button type="submit" class="btn btn-info">提交</button>';


yii\bootstrap\ActiveForm::end();
//注册ztree的静态资源和js
//注册css文件
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
//注册js文件
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$goodsCategores=json_encode(\backend\models\GoodsCategory::getZNodes());
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
     var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            view: {
		        showIcon: false
	        },
	        callback: {
		        onClick: function(event,treeId,treeNode) {
		            console.log(treeNode)
		            //获取当前点击节点的id,将它写入parent_id的值
		            $('#goods-goods_category_id').val(treeNode.id);
		            $('#goods-level').val(treeNode.level);
		        }
	        }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$goodsCategores};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);//展开全部节点
        //获取需要选中的节点
        var node= zTreeObj.getNodeByParam("id","{$model1->goods_category_id}", null);
        //根据parent_id选中节点
        zTreeObj.selectNode(node);
JS

));