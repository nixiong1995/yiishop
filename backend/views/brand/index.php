<?php
?>
<p><a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-primary">添加品牌</a></p>
<table class="table table-bordered">
    <tr>
        <th>品牌名称</th>
        <th>简介</th>
        <th>logo</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->name;?></td>
            <td><?=$model->intro;?></td>
            <td><?=yii\bootstrap\Html::img($model->logo,['class'=>'img-cricle','style'=>'width:50px'])?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->status?'正常':'隐藏'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$model->id])?>"><span class="glyphicon glyphicon-pencil btn btn-default btn-sm"></a>
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
//注册js代码
$del_url=\yii\helpers\Url::to(['brand/del']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
$('.delete').on('click',function(){
    if(confirm('确认要删除吗?')){
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
  
});
JS


));
