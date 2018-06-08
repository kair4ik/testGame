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
}
