<?php

namespace app\controllers;

use Yii;
use app\components\Parser;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use app\models\UploadForm;
use app\models\Statement;
use app\models\Transaction;
use yii\helpers\ArrayHelper;

class StatementController extends \yii\web\Controller
{
    protected $parser;

    public function __construct($id, $module, Parser $parser, $config = [])
    {
        $this->parser = $parser;
        parent::__construct($id, $module, $config);
    }
    
    public function actionIndex()
    {
        $model = new UploadForm();
        $dataProvider = new ActiveDataProvider([
            'query' => Statement::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionStore()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->statement = UploadedFile::getInstance($model, 'statement');
            if ($model->validate()) {
                $statement = new Statement();
                $statement->title = $model->statement->name;
                $statement->save();
                $html = file_get_contents($model->statement->tempName);
                $transactionsArray = $this->parser->getTransactions($html);
                Transaction::batchSave($statement->id, $transactionsArray);
            }
        }
        
        return $this->redirect(['statement/index']);
    }
    
    public function actionDelete($id) {
        $statement = Statement::findOne($id);
        $statement->delete();
        Transaction::deleteAll(['statement_id' => $id]);
        
        return $this->redirect(['statement/index']);
    }
    
    public function actionGetChartData($id) {
        if (Yii::$app->request->isAjax) {
            $transactions = Transaction::findAll(['statement_id' => $id]);
            $data = ArrayHelper::toArray($transactions, [
                'app\models\Transaction' => [
                    'time',
                    'profit'
                ],
            ]);

            Yii::$app->response->format = Response::FORMAT_JSON;
            return json_encode($data);
        }
    }
}
