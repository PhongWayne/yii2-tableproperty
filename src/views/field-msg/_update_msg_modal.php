<?php
use yii\widgets\ActiveForm;
use app\models\Language;

ActiveForm::begin([
    'id' => 'update_msg_modal',
    'action' => Yii::$app->urlManager->createAbsoluteUrl(['tableproperty/field-msg/update', 'fieldValueId' => $table_field_value_id])
]);
?>
<div class="form-msg">
</div>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Translate Languages</h4>
</div>
<div class="modal-body">
    <?php foreach($models as $fieldMsgModel => $fieldMsgModelData) { ?>
        <div class="form-group">
            <label class="control-label" for="language[<?= $fieldMsgModelData['language'] ?>]"><?= Language::getLanguageName($fieldMsgModelData['language']) ?></label>
            <input type="text" id="language[<?= $fieldMsgModelData['language'] ?>]" class="form-control" name="language[<?= $fieldMsgModelData['language'] ?>]"
            value="<?= isset($fieldMsgModelData['translation']) ? $fieldMsgModelData['translation'] : '' ?>" />
        </div>
    <?php }?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save changes</button>
</div>
<?php ActiveForm::end(); ?>