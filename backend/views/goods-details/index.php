<?php
//var_dump($models3);exit;
?>
<h2><?=$model1->name?></h2>

<?php foreach ($models3 as $model3){
        echo yii\bootstrap\Html::img($model3->path);
    }
?>

<?=$model2->content?>
