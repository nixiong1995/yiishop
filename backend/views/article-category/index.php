<?php
?>
    <p><a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-primary">添加文章</a></p>
    <table class="table table-bordered">
        <tr>
            <th>文章名称</th>
            <th>文章简介</th>
            <th>排序</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $model):?>
            <tr>
                <td><?=$model->name;?></td>
                <td><?=$model->intro;?></td>
                <td><?=$model->sort?></td>
                <td><?=$model->status?></td>
                <td>
                    <a href="<?=\yii\helpers\Url::to(['article-category/edit','id'=>$model->id])?>"><span class="glyphicon glyphicon-pencil btn btn-default btn-sm"></a>
                    <a href="<?=\yii\helpers\Url::to(['article-category/del','id'=>$model->id])?>"><span class="glyphicon glyphicon-remove btn btn-danger btn-sm" ></a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
