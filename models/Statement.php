<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\Transaction;

/**
 * This is the model class for table "statements".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 */
class Statement extends \yii\db\ActiveRecord
{
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => ['created_at'],
                'value' => new Expression('NOW()'),
            ]
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'created_at' => 'Uploaded At',
        ];
    }
    
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['statement_id' => 'id']);
    }
}
