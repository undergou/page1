<?php

namespace app\modules\page\controllers;

use app\modules\page\models\Article;
use app\modules\page\models\Category;
use app\modules\page\models\Tag;
use app\modules\page\models\Vote;
use Symfony\Component\BrowserKit\Exception\BadMethodCallException;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class StaticPageController extends Controller
{
    /**
     * @param string $slug
     *
     * @param null   $link
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionShowPage(string $slug, $link = null): string
    {
        $status = Yii::$app->user->isGuest ? Article::STATUS_GUEST : (Yii::$app->user->identity->username === "admin" ? Article::STATUS_ADMIN : Article::STATUS_USER);

        if (!($article = Article::findOne(['slug' => $slug])) instanceof Article || !$article->hasStatus($status, $link)) {
            throw new NotFoundHttpException('Static page not founded');
        }

        return $this->render('static-page.php', ['article' => $article]);
    }

    /**
     * @param string $slug
     * @param null   $page
     * @param null   $link
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionShowCategory(string $slug, $link = null): string
    {
        $status = Yii::$app->user->isGuest ? Article::STATUS_GUEST : (Yii::$app->user->identity->username === "admin" ? Article::STATUS_ADMIN : Article::STATUS_USER);

        if (!($category = Category::findOne(['slug' => $slug])) instanceof Category || !$category->hasStatus($status, $link)) {
            throw new NotFoundHttpException('Category not founded');
        }

        $query = $category->getStaticPagesByStatus($status);
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        return $this->render(
            'category.php', // TODO : REMOVE .php suffix
            [
                'category'     => $category,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * @param string $slug
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionShowTag(string $slug): string
    {
        if (!($tag = Tag::findOne(['slug' => $slug])) instanceof Tag) {
            throw new NotFoundHttpException('Tag not founded');
        }

        $status = Yii::$app->user->isGuest ? Article::STATUS_GUEST : (Yii::$app->user->identity->username === "admin" ? Article::STATUS_ADMIN : Article::STATUS_USER);
        $query = $tag->getStaticPagesByStatus($status);

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        return $this->render(
            'tag.php',
            [
                'tag'          => $tag,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * @param int  $article_id
     * @param int  $rating
     *
     * @param null $link
     *
     * @return string
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionVote(int $article_id, int $rating, $link = null)
    {
        if ($rating < 1 || $rating > 5) {
            throw new ServerErrorHttpException('Bad rating(1-5)');
        }

        if (Vote::findOne(['article_id' => $article_id, 'ip_address' => Yii::$app->request->userIP])) {
            throw new ServerErrorHttpException('You have already voted', 303);
        }

        $status = Yii::$app->user->isGuest ? Article::STATUS_GUEST : (Yii::$app->user->identity->username === "admin" ? Article::STATUS_ADMIN : Article::STATUS_USER);

        if (!($article = Article::findOne(['id' => $article_id])) instanceof Article || !$article->hasStatus($status, $link)) {
            throw new NotFoundHttpException('Static page not founded');
        }

        $vote = new Vote();
        $vote->article_id = $article_id;
        $vote->ip_address = Yii::$app->request->userIP;
        $vote->rating = $rating;
        $vote->save();

        $article->calculateRating();
        $article->save();

        return 'ok';
    }
}
