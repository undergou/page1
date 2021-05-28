<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\page\models\Article */
/* @var $categories */
/* @var $tags */

$this->title = 'Update Static Page: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Static Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="static-page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'tags' => $tags,
    ]) ?>

</div>
