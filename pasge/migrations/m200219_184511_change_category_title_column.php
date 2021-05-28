<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200219_184511_change_category_title_column
 */
class m200219_184511_change_category_title_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('article', 'category_title');
        $this->addColumn('article', 'category_id', Schema::TYPE_INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('article', 'category_id');
        $this->addColumn('article', 'category_title', Schema::TYPE_STRING);
    }
}
