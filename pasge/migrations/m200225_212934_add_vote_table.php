<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200225_212934_addVotetable
 */
class m200225_212934_add_vote_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'vote',
            [
                'ip_address' => $this->string(),
                'article_id' => $this->integer(),
                'rating'     => $this->integer(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('vote');
    }
}
