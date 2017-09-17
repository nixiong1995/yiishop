<?php
?>
<table class="table">
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->parent_id?'---':'',$model->name?></td>
        <td><?=$model->route?></td>
        <td><?=$model->sort?></td>
    </tr>
    <?php endforeach;?>
</table>
