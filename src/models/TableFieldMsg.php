<?php

namespace wayne\tableproperty\models;

use Yii;

/**
 * This is the model class for table "table_field_msg".
 *
 * @property integer $id
 * @property integer $field_value_id
 * @property string $language
 * @property string $translation
 * @property string $created
 * @property string $modified
 *
 * @property TableFieldValue $fieldValue
 */
class TableFieldMsg extends \yii\db\ActiveRecord
{
    const DEFAULT_LANGUAGE_CODE = 'en';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'table_field_msg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_value_id'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['language'], 'string', 'max' => 2],
            [['translation'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'field_value_id' => 'Field Value ID',
            'language' => 'Language',
            'translation' => 'Translation',
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
    public function getFieldValue()
    {
        return $this->hasOne(TableFieldValue::className(), ['id' => 'field_value_id']);
    }

    public static function updateViaAjax($post)
    {
        // use Yii's response format to encode output as JSON
        $_meta = $post['meta'];
        unset($post['meta']);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $fieldMsgID = end(array_keys($post));
        $_code = preg_replace('/[^\p{L}\p{N}]/', '_', strtolower($post[$fieldMsgID]));

        if(empty(trim($post[$fieldMsgID]))) {
            return ['output' => '', 'message' => Yii::t('posys', 'Please insert a name.')];
        }
        $fieldMsg = TableFieldMsg::findOne($fieldMsgID);
        $fieldValue = $fieldMsg->getFieldValue()->one();

        if($fieldValue->created == $fieldValue->modified) {
             $fieldValue->code = trim($_code);
        }
        $fieldValue->meta = $_meta;
        try{
            $fieldValue->save();
        } catch (\Exception $e) {
            switch ($e->getCode()) {
                case 23000:
                    return ['output' => '', 'message' => Yii::t('posys', 'The code belong to this name is already exist. Please insert another name.')];
                    break;
                default:
                    return ['output' => '', 'message' => 'Something error'];
            }
        }

        $fieldMsg->translation = $post[$fieldMsgID];
        if ($fieldMsg->save()) {
            return ['output' => $post[$fieldMsgID], 'message' => ''];
        }
        return ['output' => '', 'message' => 'Something error'];
    }
}
