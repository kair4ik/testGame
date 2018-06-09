<?php

namespace app\models;


use yii\helpers\Json;


/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $book_name
 * @property string $original_sugg
 */
class Task extends \yii\db\ActiveRecord
{
    public $text;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_name', 'original_sugg'], 'required'],
              ['text', 'string'],
            [['book_name', 'original_sugg'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_name' => 'Имя книги',
            'text' => 'Отрывок текста',
            'original_sugg' => 'Оригинальное предложение.',
        ];
    }

    public function createTasks()
    {
        $arrayOfTask = explode('.', $this->text);

        foreach ($arrayOfTask as $item) {

            if (self::isCorrectSize($item)) {
                self::createTask($this->book_name, $item);
            }
        }

        return true;
    }

    public static function isCorrectSize($suggestion) {
        $wordArray = explode(' ', trim($suggestion));
        if(sizeof($wordArray) > 3) {
            return true;
        }
        return false;
    }

    public static function createTask($book_name, $suggestion) {
        $task = new self();
        $task->book_name = $book_name;
        $task->original_sugg = $suggestion;
        if ($task->save()) {
            return true;
        }
        return false;
    }

    public static function getGame()
    {

        $lastGame = Statistic::find()->where(['user_id'=>\Yii::$app->user->id, 'game_result' =>'win'])->orderBy('id DESC')->one();
        $result = [];
        if (!empty($lastGame) && ($lastGame !== null)){
            $task = self::find()->where(['id'=>($lastGame->task_id +1)])->one();
        } else {
            $task = self::find()->one();
        }

        $wordsForGame = $task->original_sugg;
        $wordArray = explode(' ',$wordsForGame);
        shuffle($wordArray);
        $result['id'] = $task->id;
        $result['words'] = $wordArray;
        return $result;
    }

    public function getGameResult($suggestion)
    {
        $response = [];
        $result = "";
        if ($this->original_sugg == $suggestion) {
            $result = "win";
            Statistic::saveGameResult($this->id,$result);
            $response['status'] = $result;
            $response['text'] = "Вы распознали замысел автора <br><br>".$this->original_sugg;
            return Json::encode($response);
        } else {
            $result = "loss";
            Statistic::saveGameResult($this->id,$result);
            $response['status'] = $result;
            $response['text'] = "Увы, но автор думал иначе <br><br>".$this->original_sugg;
            return Json::encode($response);
        }

    }


}
