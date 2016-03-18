<?php

use yii\db\Migration;

class m160304_064150_create_tables_for_module_tableproperty extends Migration
{
    private static $tableOptions = null;

    public function init() {
        if ($this->db->driverName === 'mysql') {
            static::$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
    }

    public function up()
    {
        self::create_table_property();
        self::create_table_field_value();
        self::create_table_field_msg();
    }

    public function down()
    {
        self::dropAllTableModule();
    }

    private function create_table_property()
    {
        $this->createTable('{{%table_property}}', [
            'id' => $this->primaryKey(),
            'table_name' => $this->string(50),
            'field_name' => $this->string(50),
            'created' => $this->dateTime()->notNull(),
            'modified' => $this->timestamp()
        ], static::$tableOptions);
    }

    private function create_table_field_value()
    {
        $this->createTable('{{%table_field_value}}', [
            'id' => $this->primaryKey(),
            'table_property_id' => $this->integer(11),
            'order' => $this->integer(11),
            'desc' => $this->string(255),
            'created' => $this->dateTime()->notNull(),
            'modified' => $this->timestamp()
        ], static::$tableOptions);

        $this->addForeignKey('table_field_value_ibfk_1', '{{%table_field_value}}', 'table_property_id', '{{%table_property}}', 'id', 'CASCADE', 'CASCADE');
    }

    private function create_table_field_msg()
    {
        $this->createTable('{{%table_field_msg}}', [
            'id' => $this->primaryKey(),
            'field_value_id' => $this->integer(11),
            'language' => $this->string(2),
            'translation' => $this->string(255),
            'created' => $this->dateTime()->notNull(),
            'modified' => $this->timestamp()
        ], static::$tableOptions);

        $this->addForeignKey('table_field_msg_ibfk_1', '{{%table_field_msg}}', 'field_value_id', '{{%table_field_value}}', 'id', 'CASCADE', 'CASCADE');
    }

    private function dropAllTableModule()
    {
        $this->dropTable('{{%table_field_msg}}');
        $this->dropTable('{{%table_field_value}}');
        $this->dropTable('{{%table_property}}');
    }
}
