<?php

use yii\db\Migration;

/**
 * Class m200729_073404_upload_delete_history_tables
 */
class m200729_073404_upload_delete_history_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('upload_history', [
            'id' => $this->primaryKey(),
            'filename' => $this->string(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->createTable('delete_history', [
            'id' => $this->primaryKey(),
            'filename' => $this->string(),
            'created_at' => $this->integer()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('upload_history');
        $this->dropTable('delete_history');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200729_073404_upload_delete_history_tables cannot be reverted.\n";

        return false;
    }
    */
}
