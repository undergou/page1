<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\page\models\Article */
/* @var $categories */
/* @var $tags */

$this->title = 'Create Static Page';
$this->params['breadcrumbs'][] = ['label' => 'Static Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'tags' => $tags,
    ]) ?>

</div>
