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
$this->registerJsFile('https://www.gstatic.com/charts/loader.js', ['position' => yii\web\View::POS_END]);

$form = ActiveForm::begin(
    ['action' => 'statement/store'],
    ['options' => ['enctype' => 'multipart/form-data']]
);
echo $form->field($model, 'statement')->widget(FileInput::classname(), [
    'options' => ['accept' => '.html'],
    'pluginOptions' => [
        'showPreview' => false,
        'msgPlaceholder' => 'Select statement document...'
    ]
])->label('');

ActiveForm::end();

?>
<div class="text-center h4 margin-top">Statements list</div>
<?php
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
?>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
            Drag to zoom <br>
            Mouse right click to reset zoom
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="chart"></div>
      </div>
    </div>
  </div>
</div>