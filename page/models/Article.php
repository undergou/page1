<?php

namespace app\modules\page\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "article".
 *
 * @property int           $id
 * @property string|null   $title
 * @property string|null   $slug
 * @property string|null   $author
 * @property Category|null $category
 * @property string|null   $date_create
 * @property string|null   $date_update
 * @property string|null   $status
 * @property string|null   $content
 * @property string|null   $link
 * @property string|null   $short_content
 * @property int|null      $rating
 * @property               $tags
 * @property int           $category_id
 * @property               $votes
 */
class Article extends ActiveRecord
{
    const STATUS_GUEST = 'guest';
    const STATUS_USER  = 'user';
    const STATUS_ADMIN = 'admin';
    const STATUS_LINK  = 'link';
    const LIST_STATUS  = [self::STATUS_GUEST, self::STATUS_USER, self::STATUS_ADMIN, self::STATUS_LINK];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'author', 'status', 'rating', 'link'], 'required'], // TODO : Maybe category
            ['slug', 'unique'],
            [['date_create', 'date_update'], 'safe'],
            [['content', 'short_content'], 'string'],
            [['rating'], 'integer', 'min' => 1, 'max' => 5],
            [['title', 'slug', 'author', 'status'], 'string', 'max' => 255],
            ['slug', 'validateSlug'],
            ['category_id', 'validateCategory'],
            ['status', 'validateStatus'],
        ];
    }

    public function getTags()
    {
        return $this
            ->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable('article_tag', ['article_id' => 'id'])
            ;
    }

    public function getVotes()
    {
        return $this->hasMany(Vote::class, ['article_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function calculateRating(): void
    {
        $sum = 0;

        /** @var Vote $vote */
        foreach ($this->votes as $vote) {
            $sum += $vote->rating;
        }

        $this->rating = round($sum / count($this->votes));
    }

    public function getStatusList(): array
    {
        $list = [];

        foreach (self::LIST_STATUS as $status) {
            $list[$status] = $status;
        }

        return $list;
    }

    public function validateCategory($attribute, $params): void
    {
        if (!$this->hasErrors() && !Category::findOne(['id' => $this->category_id]) instanceof Category) {
            $this->addError('This category not exists');
        }
    }

    public function validateSlug($attribute, $params): void
    {
        if (!$this->hasErrors() && preg_match_all('/^[a-zA-Z0-9-]+$/', $this->slug) == 0) {
            $this->addError($attribute, 'Incorrect slug');
        }
    }

    public function validateStatus($attribute, $params): void
    {
        if (!$this->hasErrors() && !in_array($this->status, self::LIST_STATUS)) {
            $this->addError($attribute, 'Status not exists');
        }
    }

    public function validateTagsByIds($tagIds): bool
    {
        foreach ($tagIds as $tagId) { // TODO : Bad method test isExistTag, need find most best
            if (!Tag::findOne(['id' => $tagId]) instanceof Tag) {
                return false;
            }
        }

        return true;
    }

    public function setTagsByIds($tags)
    {
        $this->unlinkAll('tags', true);

        foreach ($tags as $tagId) {
            $this->link('tags', Tag::findOne(['id' => $tagId]));
        }
    }

    public function hasStatus(string $status, $link = null)
    {
        return
            $status === $this->status
            || ($status === self::STATUS_ADMIN && $this->status === self::STATUS_USER)
            || ($this->status === self::STATUS_GUEST)
            || ($this->status === self::STATUS_LINK && $this->link === $link);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'title'         => 'Title',
            'slug'          => 'Slug',
            'author'        => 'Author',
            'category_id'   => 'Category Title',
            'date_create'   => 'Date Create',
            'date_update'   => 'Date Update',
            'status'        => 'Status',
            'content'       => 'Content',
            'short_content' => 'Short Content',
            'rating'        => 'Rating',
            'link'          => 'Link',
        ];
    }
}
