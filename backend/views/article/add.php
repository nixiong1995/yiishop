<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model1,'name')->textInput();
echo $form->field($model1,'article_category_id')->dropDownList($article_category);
echo $form->field($model1,'intro')->textarea();
echo $form->field($model1,'sort')->textInput();
echo $form->field($model1,'status',['inline'=>true])->radioList([0=>'隐藏',1=>'正常']);
echo $form->field($model2,'content')->textarea()->label('文章内容');
echo '<button type="submit" class="btn btn-info">提交</button>';

yii\bootstrap\ActiveForm::end();