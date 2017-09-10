<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();
/////////////////////ZTREE插件/////////////////////////
echo '<div><ul id="treeDemo" class="ztree"></ul></div>';

/////////////////////ZTREE插件结束////////////////////
echo $form->field($model,'intro')->textarea();
echo '<button  type="submit" class="btn btn-info">提交</button>';
yii\bootstrap\ActiveForm::end();
/**
 * @var $this \yii\web\View
 * */
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
		            $('#goodscategory-parent_id').val(treeNode.id);
		        }
	        }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$goodsCategores};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);//展开全部节点
        //获取需要选中的节点
        var node= zTreeObj.getNodeByParam("id","{$model->parent_id}", null);;
        //根据parent_id选中节点
        zTreeObj.selectNode(node);
JS

));