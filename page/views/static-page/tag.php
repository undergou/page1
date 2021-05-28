<?php

/* @var $this yii\web\View */
/* @var $tag app\modules\page\models\Tag */

/* @var $dataProvider yii\data\ActiveDataProvider */

use app\modules\page\models\Article;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $tag->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-contact">
    <h1><?=Html::encode($this->title)?></h1>

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
