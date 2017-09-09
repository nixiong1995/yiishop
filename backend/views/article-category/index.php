<?php
?>
    <p><a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-primary">添加分类</a></p>
    <table class="table table-bordered">
        <tr>
            <th>分类名称</th>
            <th>分类简介</th>
            <th>排序</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $model):?>
            <tr data-id="<?=$model->id?>">
                <td><?=$model->name;?></td>
                <td><?=$model->intro;?></td>
                <td><?=$model->sort?></td>
                <td><?=$model->status?'正常':'隐藏'?></td>
                <td>
                    <a href="<?=\yii\helpers\Url::to(['article-category/edit','id'=>$model->id])?>"><span class="glyphicon glyphicon-pencil btn btn-default btn-sm"></a>
                    <a href="javascript:;" class="del-category"><span class="glyphicon glyphicon-remove btn btn-danger btn-sm" ></a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
/**
 * @var $this \yii\web\View
 */
$del_url=\yii\helpers\Url::to(['article-category/del']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        $('.del-category').on('click',function() {
            if(confirm('你确定要删除吗?')){
                var tr=$(this).closest('tr');
                var id=tr.attr('data-id');
                $.post("$del_url",{id:id},function(data) {
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
