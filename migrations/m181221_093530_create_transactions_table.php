<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transactions`.
 */
class m181221_093530_create_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transactions', [
            'id' => $this->primaryKey(),
            'statement_id' => $this->integer(),
            'time' => $this->string(),
            'profit' => $this->float()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('transactions');
    }
}
