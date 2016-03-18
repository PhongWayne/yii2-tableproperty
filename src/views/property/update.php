<?php
use yii\helpers\Html;
use app\modules\tableproperty\assets\TablePropertyAsset;
use kartik\sortinput\SortableInput;
use yii\db\Expression;
use yii\widgets\Pjax;
use app\modules\tableproperty\models\TableFieldMsg;
use kartik\editable\Editable;
use app\modules\tableproperty\models\TableFieldValue;

TablePropertyAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\TableProperty */

$this->title = 'Update Property: ' . ' ' . $tableName;
$this->params['breadcrumbs'][] = ['label' => 'Table Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update porperty ' . $tableName;
?>
<div class="table-property-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin([
                    'id' => 'property-update-panel'
                ]);?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= $this->title ?></h3>
        </div>
          <div class="panel-body">
              <!-- Table -->
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                      <th class="update-table bg-success">Field Name</th>
                      <th class="bg-success">Field Value</th>
                </thead>
                <tbody>
                 <?php
                    foreach($model as $properties => $property) {
                 ?>
                      <tr>
                          <td><b><?= $property->field_name ?></b></td>
                          <td>
                              <?php
                                  $fieldValueIdList = yii\helpers\ArrayHelper::getColumn($property->getTableFieldValues()
                                                        ->select('id')
                                                        ->orderBy('order ASC')
                                                        ->asArray()
                                                        ->all(), 'id');
                                  $arrFieldValueId = implode(',', $fieldValueIdList);
                                  $field_msgs_models = $property->getTableFieldMessages()->where(['language' => TableFieldMsg::DEFAULT_LANGUAGE_CODE]);
                                  if(!empty($arrFieldValueId)) {
                                      $field_msgs_models = $field_msgs_models->orderBy([new Expression('FIELD (field_value_id, ' . $arrFieldValueId . ')')]);
                                  }
                                  $field_msgs_models = $field_msgs_models->all();
                                  $item = [];
                                  foreach($field_msgs_models as $field_msgs => $field_msg) {
                                      $table_field_value = $field_msg->getFieldValue()->one();
                                      $is_default_checked = ($table_field_value->is_default == 1) ? 'checked' : '' ;
                                      $btn = '<div style="display: inline-block; float: right;"><label style="color: #337ab7;" class="radio-inline"><input type="radio" ' . $is_default_checked . ' value="' . $table_field_value->id . '" name="' . $property->field_name . '">Set default</label><a href="' . Yii::$app->urlManager->createUrl(['tableproperty/field-msg/update-msg-modal', 'table_field_value_id' => $table_field_value->id]) . '" data-toggle="modal" data-target="#translateMsg" title="Translate"><span class="glyphicon glyphicon-transfer"></span> Translate</a>
                              | <a name="btnDelete" fieldValueId="' . $table_field_value->id . '" href="" title="Delete"><span class="glyphicon glyphicon-trash"></span> Delete</a></div>';
                                      $inline_edit = kartik\editable\Editable::widget([
                                                           'id' => $table_field_value->id,
                                                           'name' => $field_msg->id,
                                                           'asPopover' => false,
                                                           'value' => empty(trim($field_msg->translation))? '' : $field_msg->translation,
                                                           'valueIfNull'=> 'Please enter value',
                                                           'header' => 'Name',
                                                           'size' => 'md',
                                                           'options' => [
                                                               'class' => 'form-control',
                                                               'placeholder' => yii::t('app', 'Please insert something'),
                                                               'style' => 'width: 505px'
                                                           ],
                                                           'inlineSettings' => [
                                                               'templateBefore' => Editable::INLINE_BEFORE_2,
                                                               'templateAfter' => Editable::INLINE_AFTER_2,
                                                            ],
                                                            'beforeInput' => function() {
                                                               echo '<labe>Name: </label>';
                                                            },
                                                            'afterInput' => function($form, $widget) {
                                                                $meta = TableFieldValue::findOne($widget->id)->meta;
                                                                echo '<labe>Meta: </label><br>';
                                                                echo \yii\bootstrap\Html::textarea('meta', $meta, [
                                                                    'placeholder' => 'Please insert in JSON format',
                                                                    'cols' => '60',
                                                                    'rows' => '6',
                                                                    'style' => 'resize: vertical'
                                                                ]);
                                                            }
                                                       ]);
                                      $item[$field_msg->field_value_id] = [
                                          'content' => '<i class="glyphicon glyphicon-cog"></i> ' . $inline_edit  . ' <span class="text-muted">(' . $table_field_value->code . ')</span>' . $btn
                                      ];
                                  }
                                  echo SortableInput::widget([
                                    'name'=> 'testSortable',
                                    'items' => $item,
                                    'hideInput' => true,
                                    'sortableOptions' => [
                                        'itemOptions'=>['class'=>'wayne'],
                                     ],
                                ]);
                              ?>
                              <a id="btnAdd_<?= $property->id ?>" name="btnAdd" class="btn btn-sm btn-warning" propertyId="<?= $property->id ?>" href="" title="Add"><span class="glyphicon glyphicon-plus"></span> Add new value</a>
                          </td>
                      </tr>
                  <?php } ?>
                </tbody>
              </table>
          </div>
        </div>
        <?php Pjax::end();?>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="translateMsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php
$fieldValueUpdateLink = Yii::$app->urlManager->createUrl(['tableproperty/field-value/create']);
$fieldValueSetDefaultLink = Yii::$app->urlManager->createUrl(['tableproperty/field-value/set-default']);
$fieldValueDeleteLink = Yii::$app->urlManager->createUrl(['tableproperty/field-value/delete']);
$changeOrderLink = Yii::$app->urlManager->createAbsoluteUrl(['tableproperty/field-value/change-order']);
$js_translate_msg = <<<JS
    /**
     * Removes data from modal to get new data
     * @author Wayne
     */
    $(document).on('hidden.bs.modal', '#translateMsg', function () {
        $(this).removeData('bs.modal');
    });
    /**
    * Changes order of fieldValue
    * @author Wayne
    */
    $(document).on('change', 'input[name=testSortable]', function () {
        $.ajax({
            url: "$changeOrderLink",
            method: 'POST',
            data: { val_order: $(this).val() }
        });
    });
    /**
    * Adds new TableFieldValue model
    * @author Wayne
    */
    $(document).on('click', 'a[name=btnAdd]', function () {
        $.ajax({
            url: '$fieldValueUpdateLink',
            type: 'POST',
            data: { propertyId: $(this).attr('propertyId') },
            success: function(json) {
                if(json.success == 1) {
                    $.pjax.reload({container:'#property-update-panel'});
                } else {
                    alert('PLEASE ENTER MISSING FIELD VALUE BEFORE INSERT ANOTHER ONE');
                }
            }
        });
    });
    /**
    * Deletes an existing TableFieldValue model
    * @author Wayne
    */
    $(document).on('click', 'a[name=btnDelete]', function () {
        var r = confirm('Are you sure want to delete this value ?');
        if(r == true) {
            $.ajax({
                url: '$fieldValueDeleteLink',
                method: 'POST',
                data: { fieldValueId: $(this).attr('fieldValueId') },
                success: function(json) {
                    if(json.success == 1) {
                        $.pjax.reload({container:'#property-update-panel'});
                    }
                }
            });
        }
    });
    /**
    * Selects default value
    * @author Wayne
    */
    $(document).on('click', 'input[type=radio]', function () {
        var closest_li = $(this).parents('li');
        $('li').removeClass('is-default-value');
        $.ajax({
            url: '$fieldValueSetDefaultLink',
            method: 'POST',
            data: { set_default: $(this).attr('value') },
            success: function(json) {
                if(json.success == 1) {
                    $(closest_li).addClass('is-default-value');
                }
            }
        });
    });
    $(document).on('dragstop', 'li', function () {
        console.log($(this));
    });
JS;
$this->registerJs($js_translate_msg);
?>