<?php

namespace app\models;

use app\models\Statement;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $statement_id
 * @property string $time
 * @property int $profit
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['statement_id'], 'integer'],
            [['profit'], 'number'],
            [['time'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'statement_id' => 'Statement ID',
            'time' => 'Time',
            'profit' => 'Profit',
        ];
    }
    
    public function getStatement()
    {
        return $this->hasOne(Statement::className(), ['id' => 'statement_id']);
    }
    
    public function batchSave(int $statementId, array $transactionsArray)
    {
        foreach ($transactionsArray as $transaction) {
            $model = new Transaction();
            $model->statement_id = $statementId;
            $model->time = $transaction['time'];
            $model->profit = $transaction['profit'];
            if ($model->validate()) {
                $model->save();
            }
        }
    }
}
