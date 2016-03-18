<?php

namespace wayne\tableproperty\models;

use Yii;
use yii\helpers\Json;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "table_property".
 *
 * @property integer $id
 * @property string $table_name
 * @property string $field_name
 * @property string $created
 * @property string $modified
 *
 * @property TableFieldValue[] $tableFieldValues
 */
class TableProperty extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'table_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['created'], 'required'],
            [['created', 'modified'], 'safe'],
            [['table_name', 'field_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table_name' => 'Table Name',
            'field_name' => 'Field Name',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'created',
                        'modified'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'modified'
                    ]
                ],
                'value' => new \yii\db\Expression('NOW()')
            ]
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTableFieldValues()
    {
        return $this->hasMany(TableFieldValue::className(), ['table_property_id' => 'id']);
    }

    public function getTableFieldMessages()
    {
        return $this->hasMany(TableFieldMsg::className(), ['field_value_id' => 'id'])->via('tableFieldValues');
    }

    public function getAllFieldNameByTableName($tableName)
    {
        return $this->findAll(['table_name' => $tableName]);
    }

    /**
     * Get all fields with array value inside
     * 
     * @param string $tblName Table name
     * @param string $fldName Field name
     * @param string $lang    Language
     * 
     * @return array
     */
    public static function getAllFieldValue($tblName, $fldName, $lang = TableFieldMsg::DEFAULT_LANGUAGE_CODE)
    {
        $data = (new Query())
            ->select(['fld.id', 'fld.code', 'fld.is_default', 'fld.meta', 'msg.translation'])
            ->from('table_property pro')
            ->leftJoin('table_field_value fld', 'fld.table_property_id = pro.id')
            ->leftJoin('table_field_msg msg', 'msg.field_value_id = fld.id')
            ->where(['pro.table_name' => $tblName, 'pro.field_name' => $fldName, 'msg.language' => $lang])
            ->orderBy('order')
            ->all();
        $result = [];
        foreach ($data as $value) {
            $value['meta'] = Json::decode($value['meta']);
            $result[] = $value;
        }
        return $result;
    }
    
    /**
     * Get one field. The function return an array value of field
     * (id, code, is_default, meta, translation) base on [table_name] and [field_name] 
     * You can easily specify a value type by pass to $valType one of elements list above
     * 
     * @param string $tblName Table name
     * @param string $fldName Field name
     * @param integer $fldId   Field id
     * @param string $valType Value type
     * @param string $lang Language
     * 
     * @return array An array value of field
     */
    public static function getFieldValue($tblName, $fldName, $fldId, $valType = null, $lang = TableFieldMsg::DEFAULT_LANGUAGE_CODE)
    {
        $result = (new Query())
            ->select(['fld.id', 'fld.code', 'fld.is_default', 'fld.meta', 'msg.translation'])
            ->from('table_property pro')
            ->leftJoin('table_field_value fld', 'fld.table_property_id = pro.id')
            ->leftJoin('table_field_msg msg', 'msg.field_value_id = fld.id')
            ->where(['pro.table_name' => $tblName, 'pro.field_name' => $fldName, 'fld.id' => $fldId, 'msg.language' => $lang])
            ->one();

        if($result['meta']) {
            $result['meta'] = Json::decode($result['meta']);
        }

        if ($valType === null) {
            return $result;
        } else {
            return $result[$valType];
        }
    }

    /**
     * Function execute tag
     *
     * @author Alex
     * @param array $tag_arr
     * @param string $lang
     * @return array
     */
    public static function exeTag($tag_arr = [])
    {
        $current_tag = ArrayHelper::map(self::getAllFieldValue('po_add_book', 'company_tag'), 'id', 'translation');
        $result_id = [];
        if (!empty($tag_arr)) {
            foreach (array_values($tag_arr) as $value) {
                if (!in_array($value, array_keys($current_tag))) {
                    unset($tag_arr[array_search($value, $tag_arr)]);
                    $model_field = new TableFieldValue;
                    $model_field->table_property_id = self::find()->where(['table_name' => 'po_add_book', 'field_name' => 'company_tag'])->one()->id;
                    $model_field->created = date('Y-m-d H:i:s');
                    $model_field->save();

                    $model_message = new TableFieldMsg;
                    $model_message->language = TableFieldMsg::DEFAULT_LANGUAGE_CODE;
                    $model_message->field_value_id = $model_field->id;
                    $model_message->translation = $value;
                    $model_message->created = date('Y-m-d H:i:s');
                    $model_message->save();

                    $result_id[] = (string) $model_field->id;
                }
            }
        }
        return !empty($tag_arr)?array_merge($tag_arr, $result_id):array();
    }
}
