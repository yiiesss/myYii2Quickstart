<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use common\models\Document;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index', [ ]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Lists all Document models.
     * @return mixed
     */
    public function actionDetails() {
        $model = new Document();
        $request = Yii::$app->request;
        $name = $request->get('name');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');

//        $dataProvider = $model->find()->with([
//                    'documentType' => function ($query) {
//                        $query->andWhere(['type' => $name]);
//                    },])
//                        ->andWhere(['>=','dateAjout' , $date_debut])
//                         ->andWhere(['<=','dateAjout' , $date_fin])
//                        ->all();

        $query = Document::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['documentType']);
        $query->joinWith(['documentType' => function ($q) {
                $q->where('documentType.type = "' . Yii::$app->request->get("name") . '"');
            }]);
        $query->andWhere('dateAjout >= "' . $date_debut . '" ');
        $query->andWhere('dateAjout <= "' . $date_fin . '" ');
        return $this->render('details', [
                    'dataProvider' => $dataProvider,
        ]);
    }

}
