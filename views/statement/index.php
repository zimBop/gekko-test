<?php

use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $result array */
 
$this->title = 'Statements';

$this->registerJsFile('@web/js/statement.js', ['depends' => 'yii\web\YiiAsset', 'position' => yii\web\View::POS_END]);

$form = ActiveForm::begin(
    ['action' => 'store'],
    ['options' => ['enctype' => 'multipart/form-data']]
);
echo $form->field($model, 'statement')->widget(FileInput::classname(), [
    'options' => ['accept' => '.html'],
]);

ActiveForm::end();

echo '<div class="text-center"></div>';

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'title',
        'created_at:datetime',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'template' => '{chart}&nbsp;&nbsp;{delete}',
            'buttons' => [
                'chart' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-signal"></span>', 
                        Url::to(['statement/get-chart-data?id=' . $model->id]),
                        [
                            'title' => 'Chart',
                            'class' => 'chart-button',
                            'data-id' => $model->id,
                        ]
                    );
                }
            ]
        ]
    ],
]);
