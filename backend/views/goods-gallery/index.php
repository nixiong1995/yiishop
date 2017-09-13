<?php
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
?>
<table class="table">
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr data-id="<?=$model->id?>">
        <td><?=yii\bootstrap\Html::img($model->path)?></td>
        <td>
            <a href="javascript:;" class="btn btn-danger">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
$url_Add=\yii\helpers\Url::to(['goods-gallery/add']);
    echo \flyok666\uploadifive\Uploadifive::widget([
        'url' => yii\helpers\Url::to(['s-upload']),
        'id' => 'test',
        'csrf' => true,
        'renderTag' => false,
        'jsOptions' => [
            'formData'=>['someKey' => 'someValue'],
            'width' => 120,
            'height' => 40,
            'onError' => new \yii\web\JsExpression(<<<EOF
    function(file, errorCode, errorMsg, errorString) {
        console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
    }
EOF
            ),
            'onUploadComplete' => new \yii\web\JsExpression(<<<EOF
    function(file, data, response) {
        data = JSON.parse(data);
        if (data.error) {
            console.log(data.msg);
        } else {
            console.log(data.fileUrl);
            //图片回显
            $("#img").attr("src",data.fileUrl);
            //上传文件后将路径保存到数据库
            var id={$id};
            $.post("$url_Add",{id:id,path:data.fileUrl},function(data){
                    if(data=="success"){
                        console.debug('路径保存数据库成功');
                    }else{
                        console.debug('路径保存数据库失败');
                    } 
            })
        }
    }
EOF
            ),
        ]
    ]);
/**
 * @var $this \yii\web\View
 */
$url_Del=\yii\helpers\Url::to(['goods-gallery/del']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        $('.btn-danger').on('click',function(){
          if(confirm('你确定要删除吗?')){
              var tr=$(this).closest('tr');
              var id=tr.attr('data-id');
              $.post("$url_Del",{id:id},function(data) {
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
