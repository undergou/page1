<?php

/* @var $this yii\web\View */

/* @var $article app\modules\page\models\Article */

use app\modules\page\widgets\RatingWidget;
use yii\helpers\Html;

$this->title = $article->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-contact">
    <h1><?=Html::encode($this->title)?></h1>
    <h6><?=$article->date_create?></h6>
    <h3>Tags: <?=implode(
            ',',
            array_map(
                function ($tag) {
                    return Html::a($tag->title, ['/page/tag/'.$tag->slug]);
                },
                $article->tags
            )
        )?></h3>
    <div class="article-content">
        <?=$article->content?>
    </div>
    <?=RatingWidget::widget(['article' => $article])?>
</div>
