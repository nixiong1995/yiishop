<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status',['inline'=>true])->radioList([0=>'隐藏',1=>'显示']);
echo $form->field($model,'code')->widget(yii\captcha\Captcha::className(),[
    'captchaAction'=>'article-category/captcha']);
echo '<button type="submit" class="btn btn-info">提交</button>';
yii\bootstrap\ActiveForm::end();