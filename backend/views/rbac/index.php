<?php
?>
<p><a href="<?=\yii\helpers\Url::to(['rbac/add'])?>" class="btn btn-primary">添加权限</a></p>
    <table id="table_id_example" class="table">
        <thead>
            <tr>
                <th>权限名称</th>
                <th>权限描述</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($permissions as $permission):?>
            <tr data-name="<?=$permission->name?>">
                <td><?=$permission->name?></td>
                <td><?=$permission->description?></td>
                <td>
                    <a href="<?=\yii\helpers\Url::to(['rbac/edit','name'=>$permission->name])?>"><span class="glyphicon glyphicon-pencil btn btn-primary btn-sm" ></a>
                    <a href="javascript:;" class="delete"><span class="glyphicon glyphicon-trash btn btn-danger btn-sm"></a>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile("@web/datatables/media/css/jquery.dataTables.css");
$this->registerJsFile("@web/datatables/media/js/jquery.dataTables.js",['depends'=>\yii\web\JqueryAsset::className()]);
$del_url=\yii\helpers\Url::to(['rbac/del']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        $('.delete').on('click',function() {
            var tr=$(this).closest('tr');
            var name=tr.attr('data-name');
            if(confirm('你确定要删除吗?')){
               $.post("$del_url",{name:name},function(data) {
                   if(data=='success'){
                       alert('删除成功');
                       tr.hide('slow');
                   }else{
                       alert('删除失败');
                   }
               }) 
            }
        })
        $(document).ready( function () {
    $('#table_id_example').DataTable();
} );

JS

));

