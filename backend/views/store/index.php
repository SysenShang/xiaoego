<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use common\models\Store;
use common\models\School;

$this->title = '营业点列表';
?>
<p>
    <?= Html::a('<i class="fa fa-plus"></i> 添加营业点', ['store/add'], ['class' => 'btn btn-primary']) ?>
</p>
<div class="row">
    <div class="col-lg-12">
        <?php Pjax::begin() ?>
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-bordered table-center'],
                'summaryOptions' => ['tag' => 'p', 'class' => 'text-right text-info'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['class' => 'col-md-1']
                    ],
                    [
                        'attribute' => 'name',
                        'headerOptions' => ['class' => 'col-md-2'],
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
                    ],
                    [
                        'attribute' => 'school_id',
                        'headerOptions' => ['class' => 'col-md-3'],
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
                        'value' => function ($model, $key, $index, $column) {
                            return $model->school->name;
                        },
                        'filter' => Select2::widget([
                            'model' => $searchModel,
                            'initValueText' => ($school = School::findOne($searchModel->school_id)) ? $school->name : '' ,
                            'attribute' => 'school_id',
                            'size' => Select2::SMALL,
                            'theme' => Select2::THEME_KRAJEE,
                            'options' => ['placeholder' => '搜索学校名称...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'ajax' => [
                                    'url' => Url::to(['/school/name-filter']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function (store) { return store.text; }'),
                                'templateSelection' => new JsExpression('function (store) { return store.text; }'),
                            ]
                        ]),
                    ],
                    [
                        'attribute' => 'address',
                        'headerOptions' => ['class' => 'col-md-2'],
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'filter' => Store::getStatusList(),
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
                        'headerOptions' => ['class' => 'col-md-1'],
                        'value' => function ($model, $key, $index, $column) {
                            return Html::dropDownList('status', $model->status, Store::getStatusList(), ['data-id' => $model->id]);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:Y-m-d H:i'],
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'attribute' => 'date',
                            'options' => ['class' => 'input-sm'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]),
                        'headerOptions' => ['class' => 'col-md-2']
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'headerOptions' => ['class' => 'col-md-1'],
                        'template' => '{update} {view}'
                    ]
                ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>
<?php
$url = Url::to(['/store/status']);
$js = <<<JS
var handle = function () {
    var id = $(this).attr('data-id');
    var status = $(this).val();
    $.ajax({
        url: '{$url}?id=' + id ,
        type: 'post',
        dataType: 'json',
        data: {status: status},
        success: function () {},
        error: function () {}
    });
};
$('select[name="status"]').change(handle);

$(document).on('pjax:complete', function() {
    $('select[name="status"]').change(handle);
})
JS;

$this->registerJs($js);
?>