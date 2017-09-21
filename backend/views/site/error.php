<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>
    <button class="btn btn-default">返回</button>
</div>
<?php
/**
 * @var $this \yii\web\View
 * */
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        $('.btn-default').on('click',function() {
            history.back();
          
        })

JS

));
