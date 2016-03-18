<?php

use yii\db\Migration;

class m160318_105850_alter_table_property_module extends Migration
{
    public function up()
    {
        self::alter_table_property();
        self::alter_table_field_value();
        self::alter_table_field_msg();
    }

    public function down()
    {
        return false;
    }

    private function alter_table_property()
    {
        $this->createIndex('unique_table_field', '{{%table_property}}', ['table_name', 'field_name']);
    }

    private function alter_table_field_value()
    {
        $this->addColumn('{{%table_field_value}}', 'code', $this->string(100)->notNull());
        $this->addColumn('{{%table_field_value}}', 'is_default', $this->boolean()->defaultValue(0));
        $this->addColumn('{{%table_field_value}}', 'meta', $this->text());

        $this->createIndex('unique_code_depend_on_table_property_id', '{{%table_field_value}}', ['table_property_id', 'code']);
    }

    private function alter_table_field_msg()
    {
        $this->createIndex('table_field_msg_ibfk_1', '{{%table_field_msg}}', ['field_value_id']);
    }
}
