<?php

namespace targetmedia\tableproperty\models;

use Yii;

/**
 * This is the model class for table "table_field_value".
 *
 * @property integer $id
 * @property integer $table_property_id
 * @property string  $code
 * @property integer $is_default
 * @property string  $meta
 * @property integer $order
 * @property string  $desc
 * @property string  $created
 * @property string  $modified
 *
 * @property TableFieldMsg[] $tableFieldMsgs
 * @property TableProperty $tableProperty
 */
class TableFieldValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'table_field_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_property_id', 'order', 'is_default'], 'integer'],
            [['code', 'created'], 'required'],
            [['meta'], 'string'],
            [['created', 'modified'], 'safe'],
            [['code'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('posys', 'ID'),
            'table_property_id' => Yii::t('posys', 'Table Property ID'),
            'code' => Yii::t('posys', 'Code'),
            'is_default' => Yii::t('posys', 'Is Default'),
            'meta' => Yii::t('posys', 'Meta'),
            'order' => Yii::t('posys', 'Order'),
            'desc' => Yii::t('posys', 'Desc'),
            'created' => Yii::t('posys', 'Created'),
            'modified' => Yii::t('posys', 'Modified'),
        ];
    }

    /**
     * @inheritdoc
     */
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
    public function getTableFieldMsgs()
    {
        return $this->hasMany(TableFieldMsg::className(), ['field_value_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTableProperty()
    {
        return $this->hasOne(TableProperty::className(), ['id' => 'table_property_id']);
    }

    public function getAllFieldValueById($id)
    {
        return $this->findAll($id);
    }
}
