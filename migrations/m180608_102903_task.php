<?php

use yii\db\Migration;

/**
 * Class m180608_102903_task
 */
class m180608_102903_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'book_name' => $this->string()->notNull(),
            'original_sugg' => $this->string()->notNull(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180608_102903_task cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180608_102903_task cannot be reverted.\n";

        return false;
    }
    */
}
