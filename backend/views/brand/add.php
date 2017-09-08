<?php
use yii\web\JsExpression;
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
/////////////////uploadifive插件//////////////////
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将文件路径写入logo字段隐藏域
        $("#brand-logo").val(data.fileUrl);
        //图片回显
        $("#img").attr("src",data.fileUrl);
    }
}
EOF
        ),
    ]
]);
////////////////////////////////////////////////////
echo yii\bootstrap\Html::img($model->logo,['class'=>'img-cricle','style'=>'width:150px','id'=>'img','padding-top'=>10]);
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status',['inline'=>true])->radioList([0=>'隐藏',1=>'显示']);
echo $form->field($model,'code')->widget(yii\captcha\Captcha::className(),[
    'captchaAction'=>'brand/captcha']);
echo '<button type="submit" class="btn btn-info">提交</button>';
yii\bootstrap\ActiveForm::end();