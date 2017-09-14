<?php
?>
<p><a href="<?=\yii\helpers\Url::to(['goods/add'])?>" class="btn btn-primary">添加商品</a></p>
<form class="form-inline" method="get" action="<?=\yii\helpers\Url::to(['goods/index'])?>">
    <input type="text" name="name" class="form-control" placeholder="商品名"/>
    <input type="text" name="sn" class="form-control"placeholder="货号"/>
    <input type="text" name="price_begin" class="form-control" placeholder="￥" >
    <input type="text" name="price_end" class="form-control" placeholder="￥" >
    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search">搜索</span></button>
</form>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>logo</th>
        <th>价格</th>
        <th>库存</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id;?></td>
            <td><?=$model->name;?></td>
            <td><?=$model->sn;?></td>
            <td><?=yii\bootstrap\Html::img($model->logo,['class'=>'img-cricle','style'=>'width:50px'])?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$model->id])?>"><span class="glyphicon glyphicon-pencil btn btn-default btn-sm"></a>
                <a href="javascript:;" class="delete"><span class="glyphicon glyphicon-remove btn btn-danger btn-sm" ></a>
                <a href="<?=\yii\helpers\Url::to(['goods-gallery/index','id'=>$model->id])?>"><span class="glyphicon glyphicon-picture btn btn-primary btn-sm" ></a>
                <a href="<?=\yii\helpers\Url::to(['goods-details/index','id'=>$model->id])?>"><span class="glyphicon glyphicon-file btn btn-success btn-sm" ></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
/**
 *@var $this\yii\web\View
 */
//注册js代码
$url_del=\yii\helpers\Url::to(['goods/del']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        $('.delete').on('click',function() {
            if(confirm('你确定要删除吗?')){
                var tr=$(this).closest('tr');
                var id=tr.attr('data-id');
                $.post("$url_del",{id:id},function(data) {
                    if(data=='success'){
                        alert('删除成功');
                        tr.hide('slow');
                    }else{
                        alert('删除失败');
                    }
                  
                })
            }
          
        })

JS

));