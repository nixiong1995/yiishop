<?php
?>
<script type="text/javascript" src="/js/jsAddress.js"></script>
<form action="<?=\yii\helpers\Url::to(['locations/edit','id'=>$model->id])?>" method="post" name="address_form" >
    <div class="form-group">
            <label for=""><span>*</span>收 货 人：</label>
            <input type="text" name="name" class="txt" value="<?=$model->name?>"/>
    </div>
        <div class="form-group">
            <label for=""><span>*</span>所在地区：</label>
            <select id="defaultProvince" name="province"></select>
            <select id="defaultCity" name="city"></select>
            <select id="defaultArea" name="region"></select>
        </div>

            <script type="text/javascript">
                //cmb.options.add("$model");
                addressInit('defaultProvince','defaultCity','defaultArea','<?=$model->province?>','<?=$model->city?>','<?=$model->region?>');
            </script>

    <div class="form-group">
            <label for=""><span>*</span>详细地址：</label>
            <input type="text" name="address" class="txt address" style="width:300px;" value="<?=$model->address?>" />
    </div>
    <div class="form-group">
            <label for=""><span>*</span>手机号码：</label>
            <input type="text" name="phone" class="txt" value="<?=$model->phone?>" />
    </div>
    <div class="form-group">
            <label for="">&nbsp;</label>
            <input type="checkbox" name="" class="check" />设为默认地址
    </div>
    <div class="form-group">
            <label for="">&nbsp;</label>
            <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>"/>
            <input type="submit" name="" class="btn btn-info" value="保存" />
    </div>
</form>
