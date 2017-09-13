<?php
?>
    <p><a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-primary">添加分类</a></p>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>分类名称</th>
            <th>分类简介</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $model):?>
            <tr data-id="<?=$model->id?>">
                <td><?=$model->id;?></td>
                <td><?=str_repeat('--',$model->depth)?><?=$model->name;?></td>
                <td><?=$model->intro;?></td>
                <td>
                    <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$model->id])?>"><span class="glyphicon glyphicon-pencil btn btn-default btn-sm"></a>
                    <a href="javascript:;" class="delete"><span class="glyphicon glyphicon-remove btn btn-danger btn-sm" ></a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
    <div class="text-muted">合计<?=$pager->totalCount?>条</div>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
/**
 * @var $this \yii\web\View
*/
$url_del=\yii\helpers\Url::to(['goods-category/del']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
$('.delete').on('click',function() {
    if(confirm('你确定要删除吗?')){
        var tr=$(this).closest('tr');
        var id=tr.attr('data-id');
        $.post("$url_del",{id:id},function(data) {
            if(data=='success'){
                alert('删除成功');
                tr.hide('slow')
            }else if(data=='prohibit'){
                alert('该分类下有子分类,不允许删除');
            }else{
                alert('删除失败');
            }
        })
    }
  
})
JS

));