<?php

namespace app\modules\page\controllers;

use app\modules\page\models\Category;
use app\modules\page\models\Tag;
use app\modules\page\models\Vote;
use Yii;
use app\modules\page\models\Article;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminArticleController implements the CRUD actions for Article model.
 */
class AdminArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->getIdentity(true)->username === 'admin';
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider(
            [
                'query' => Article::find(),
                'pagination' => [
                    'pageSize' => 10
                ],
            ]
        );

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Displays a single Article model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render(
            'view',
            [
                'model'      => $this->findModel($id),
                'categories' => Category::find(),
            ]
        );
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post())) {
            $model->date_create = date('Y-m-d');
            $model->date_update = $model->date_create;

            $tags = Yii::$app->request->post()['Article']['tags'] === '' ? [] : Yii::$app->request->post()['Article']['tags'];
            if (isset(Yii::$app->request->post()['Article']['category'])) {
                $model->category_id = Yii::$app->request->post()['Article']['category'];
            }

            if ($model->validateTagsByIds($tags) && $model->save()) {
                $model->setTagsByIds($tags);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render(
            'create',
            [
                'model'      => $model,
                'categories' => Category::getTitleList(),
                'tags'       => Tag::getTitleList(),
            ]
        );
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $tags = Yii::$app->request->post()['Article']['tags'] === '' ? [] : Yii::$app->request->post()['Article']['tags'];
            if (isset(Yii::$app->request->post()['Article']['category'])) {
                $model->category_id = Yii::$app->request->post()['Article']['category'];
            }
            $model->date_update = date('Y-m-d');
            $oldRating = $model->oldAttributes['rating'];

            if ($model->validateTagsByIds($tags) && $model->save()) {
                $model->setTagsByIds($tags);

                if ($oldRating !== $model->rating) {
                    $model->unlinkAll('votes', true);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render(
            'update',
            [
                'model'      => $model,
                'categories' => Category::getTitleList(),
                'tags'       => Tag::getTitleList(),
            ]
        );
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
