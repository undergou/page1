<?php

/* @var $this yii\web\View */
/* @var $category app\modules\page\models\Category */

/* @var $dataProvider yii\data\ActiveDataProvider */

use app\modules\page\models\Article;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $category->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-contact">
    <h1><?=Html::encode($this->title)?></h1>

    <?php if (!empty(($subcategories = $category->getSubcategories()))) : ?>
        <h3>Subcategories:</h3>
        <ul>
            <?php foreach ($subcategories as $subcategory) : ?>
                <li><?=Html::a($subcategory->title, ['page/category/'.$subcategory->slug])?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif ?>
    <h3>Articles:</h3>
    <?=GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],

                'title',
                'date_update',
                'short_content',

                [
                    'attribute' => 'Action',
                    'value' => function (Article $article) {
                        return Html::a('View', Url::to(['/page/' . $article->slug]));
                    },
                    'format' => 'raw',
                ],
            ],
        ]
    );?>
</div>
