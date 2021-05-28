<?php

namespace app\modules\page\widgets;

use Exception;
use yii\base\Widget;

class RatingWidget extends Widget
{
    public $article;

    /**
     * @throws Exception
     */
    public function init()
    {
        parent::init();

        if ($this->article === null) {
            throw new Exception('Need set article');
        }
    }

    public function run()
    {
        return $this->render('/widgets/rating_widget.php', ['article' => $this->article]);
    }
}