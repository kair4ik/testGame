<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "statistic".
 *
 * @property int $id
 * @property int $user_id
 * @property int $task_id
 * @property string $game_result
 */
class Statistic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'task_id', 'game_result'], 'required'],
            [['user_id', 'task_id'], 'integer'],
            [['game_result'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'task_id' => 'Task ID',
            'game_result' => 'Game Result',
        ];
    }

    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /*
     * Сохраняем результат игры
     * */
    public static function saveGameResult($gameId,$result){
        $statistic = new self();
        $statistic->task_id = $gameId;
        $statistic->user_id = Yii::$app->user->id;
        $statistic->game_result = $result;
        $statistic->save();
    }
    /*
     * Вся статистика целиком
     * */
    public static function getStatistic(){
        $result['amountGames'] = self::amountGames();
        $result['averageWin'] = self::getAverage('win');
        $result['averageLoss'] = self::getAverage('loss');
        $result['bigData'] = self::getStatisticForBooks();

        return $result;
    }

    /*
     * Получаем кол-во игр по каждому сыгранному произведению
     * */
    public static function getStatisticForBooks() {
        $books = self::getBookList();
        $result = [];
        foreach ($books as $book) {
            $result[$book] = self::getAmountGamesByBook($book);
        }
        return $result;
    }

    /*
     * Считаем кол-во игр для юзера по произведению
     * */
    public static function getAmountGamesByBook($bookName) {
        return self::find()
            ->joinWith('task', true)
            ->where(['book_name' => $bookName])
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->count();
    }

    /*
     * Возвращаем список произведений
     * */
    public static function getBookList() {
        $result = array_map(function($item) {
            return $item->book_name;
        },Task::find()->all());
        return array_unique($result);
    }

    /*
     * Возвращаем список игравших игроков
     * */
    public static function getUserList() {
        $result = array_map(function($item) {
            return $item->user_id;
        },self::find()->all());
        return array_unique($result);
    }

    /*
     * Cреднее число побед, это победы всех пользователей деленные на их кол-во
     * */
    public static function getAverage($param = "win") {
        $arrayUsers = self::getUserList();

        $arrayGames = array_map(function($user) use ($param) {
            if ($param == "win"){
                return self::getAmountWinForUser($user);
            } else if ($param == "loss"){
                return self::getAmountLossForUser($user);
            }
        },$arrayUsers);

        $average = array_sum($arrayGames) / sizeof($arrayGames);
        return $average;
    }

    /*
     * Считаем кол-во побед юзера
     * */
    public static function getAmountWinForUser($user_id){
        $result = Statistic::find()->where(['user_id'=>$user_id,'game_result' => 'win'])->count();
        return $result;
    }

    /*
     * Считаем кол-во проигрышей юзера
     * */
    public static function getAmountLossForUser($user_id){
        $result = Statistic::find()->where(['user_id'=>$user_id,'game_result' => 'loss'])->count();
        return $result;
    }

    /*
    * Считаем кол-во побед текущего юзера
    * */
    public static function getAmountWin(){
        $result = Statistic::find()->where(['user_id'=>Yii::$app->user->id,'game_result' => 'win'])->count();
        return $result;
    }

    /*
     * Считает кол-во игр текущего юзера
     * */
    public static function amountGames() {
        $result = Statistic::find()->where(['user_id'=>Yii::$app->user->id])->count();
        return $result;
    }
}
