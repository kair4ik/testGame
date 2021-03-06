<?php

namespace app\controllers;

use app\models\Statistic;
use app\models\Task;
use app\models\User;
use yii\helpers\Json;
use yii\helpers\Url;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $game = Task::getGame();

        return $this->render('index',
            [
                'game' => $game,
            ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        $status = "";
        $model = new Task();
        if($model->load(\Yii::$app->request->post())){

            if ($model->createTasks()) {
                $status = "Запись успешно сохранена";
            } else {
                $status = $model->getErrors();
            }
        }

        return $this->render('about',[
            'model' => $model,
            'status' => $status,
        ]);
    }

    public function actionAddUser($username, $password) {
        $model = User::find()->where(['username' => $username])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = $username;
//            $user->email = 'admin@mail.ru';
            $user->setPassword($password);
            $user->generateAuthKey();
            if ($user->save()) {
                echo 'Вы успешно зарегистрировались! Используйте новые данные для входа.';
                $url = Url::to(['login']);
                echo  Html::a('Войти',$url);
            }
        }
    }

    public function actionGetResult($gameId,$suggestion)
    {
        $game = Task::findOne(['id'=>$gameId]);
        $result = $game->getGameResult($suggestion);
        echo $result;
    }

    public function actionGetStat() {
        $stat = Statistic::getStatistic();
        $response = Json::encode($stat);
        return $response;

    }


}
