<?php

use yii\db\Migration;

/**
 * Class m200301_131915_add_link_for_category
 */
class m200301_131915_add_link_for_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('category', 'link', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('category', 'link');
    }
}
