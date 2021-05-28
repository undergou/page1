<?php

use yii\db\Migration;

/**
 * Class m200301_102432_add_link_collumn_for_article
 */
class m200301_102432_add_link_collumn_for_article extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('article', 'link', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('article', 'link');
    }
}
