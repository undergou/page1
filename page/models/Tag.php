<?php

namespace app\modules\page\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "tag".
 *
 * @property int         $id
 * @property string|null $title
 * @property string|null $slug
 */
class Tag extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'slug'], 'string', 'max' => 255],
            ['slug', 'validateSlug'],
        ];
    }

    public function getStaticPages()
    {
        return $this
            ->hasMany(Article::class, ['id' => 'article_id'])
            ->viaTable('article_tag', ['tag_id' => 'id'])
            ;
    }

    public function getStaticPagesByStatus(string $userStatus)
    {
        return $this
            ->getStaticPages()
            ->where(
                [
                    'or',
                    [
                        'or',
                        ['status' => $userStatus],
                        [
                            'status' => $userStatus == Article::STATUS_ADMIN ? Article::STATUS_USER
                                : Article::STATUS_GUEST,
                        ],
                    ],
                    ['status' => Article::STATUS_GUEST],
                ]
            )
            ;
    }

    public function validateSlug($attribute, $params): void
    {
        if (!$this->hasErrors() && preg_match_all('/^[a-zA-Z0-9-]+$/', $this->slug) == 0) {
            $this->addError($attribute, 'Incorrect slug');
        }
    }

    public function __toString()
    {
        return $this->title;
    }

    public static function getTitleList(): array
    {
        $list = [];

        /** @var Tag $tag */
        foreach (Tag::find()->all() as $tag) {
            $list[$tag->id] = $tag->title;
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'    => 'ID',
            'title' => 'Title',
            'slug'  => 'Slug',
        ];
    }
}
