<?php

namespace app\modules\page\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property int         $id
 * @property string|null $title
 * @property int|null    $id_parent
 * @property string|null $slug
 * @property string|null $link
 * @property string|null $status
 */
class Category extends ActiveRecord
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
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'slug', 'title', 'id_parent', 'link'], 'required'],
            ['id_parent', 'integer'],
            ['id_parent', 'validateParentId'],
            ['status', 'validateStatus'],
            ['slug', 'validateSlug'],
            [['title', 'slug', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'title'     => 'Title',
            'id_parent' => 'ID of Parent',
            'slug'      => 'Slug',
            'status'    => 'Status',
            'link'      => 'Link',
        ];
    }

    public function getStaticPages()
    {
        return $this->hasMany(Article::class, ['category_id' => 'id']);
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

    public function hasStatus(string $status, $link = null)
    {
        return
            $status === $this->status
            || ($status === self::STATUS_ADMIN && $this->status === self::STATUS_USER)
            || ($this->status === self::STATUS_GUEST)
            || ($this->status === self::STATUS_LINK && $this->link === $link);
    }

    public function validateParentId($attribute, $params): void
    {
        if (!$this->hasErrors() && $this->id_parent == $this->id || $this->id_parent != -1 && !Tag::findOne(
                    ['id' => $this->id_parent]
                ) instanceof Tag) {
            $this->addError($attribute, 'This tag not exists');
        }
    }

    public function validateStatus($attribute, $params): void
    {
        if (!$this->hasErrors() && !in_array($this->status, self::LIST_STATUS)) {
            $this->addError($attribute, 'Status not exists');
        }
    }

    public function validateSlug($attribute, $params): void
    {
        if (!$this->hasErrors() && preg_match_all('/^[a-zA-Z0-9-]+$/', $this->slug) == 0) {
            $this->addError($attribute, 'Incorrect slug');
        }
    }

    public function getStatusList(): array
    {
        $list = [];

        foreach (self::LIST_STATUS as $status) {
            $list[$status] = $status;
        }

        return $list;
    }

    public function getSubcategories()
    {
        return Category::findAll(['id_parent' => $this->id]); // TODO : Check user status
    }

    public static function getTitleList(): array
    {
        $list = [];

        /** @var Category $category */
        foreach (Category::find()->all() as $category) {
            $list[$category->id] = $category->title;
        }

        return $list;
    }
}
