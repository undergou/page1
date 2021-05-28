<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200218_160632_init
 */
class m200218_160632_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'article',
            [
                'id'             => Schema::TYPE_PK,
                'title'          => Schema::TYPE_STRING,
                'slug'           => $this->string()->unique(),
                'author'         => Schema::TYPE_STRING,
                'category_title' => Schema::TYPE_STRING,
                'date_create'    => Schema::TYPE_DATE,
                'date_update'    => Schema::TYPE_DATE,
                'status'         => Schema::TYPE_STRING,
                'content'        => Schema::TYPE_TEXT,
                'short_content'  => Schema::TYPE_TEXT,
                'rating'         => Schema::TYPE_INTEGER,
            ]
        );

        $this->createTable(
            'category',
            [
                'id'        => Schema::TYPE_PK,
                'title'     => Schema::TYPE_STRING,
                'id_parent' => Schema::TYPE_INTEGER,
                'slug'      => $this->string()->unique(),
                'status'    => Schema::TYPE_STRING,
            ]
        );

        $this->createTable(
            'tag',
            [
                'id'    => Schema::TYPE_PK,
                'title' => Schema::TYPE_STRING,
                'slug'  => $this->string()->unique(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
        $this->dropTable('category');
        $this->dropTable('tag');
    }
}
