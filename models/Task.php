<?php

namespace app\models;

use Yii;

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

    public function getGameResult()
    {

    }
}
