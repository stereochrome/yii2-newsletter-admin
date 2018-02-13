<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;


$this->title = Yii::t('newsletter-admin', 'Newsletter-Templates');
$this->params['breadcrumbs'][] = $this->title;

$mainActions = [

	['label' => Yii::t('newsletter-admin', 'Create Template'), 'url' => ['create'], 'options' => ['class' => 'btn btn-md btn-primary']],

]


?>


<?php $this->beginContent('@Stereochrome/NewsletterAdmin/resources/views/shared/admin_layout.php', ['mainActions' => $mainActions]) ?>



<?php Pjax::begin() ?>

<?= GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => [
            'id',
            'name',
            'identifier',

            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return Yii::t('newsletter-admin', '{0, date, MMMM dd, YYYY HH:mm}', [$model->updated_at]);
                },
                'filter' => false,
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return Yii::t('newsletter-admin', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                },
                'filter' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]
); ?>

<?php Pjax::end() ?>

<?php $this->endContent() ?>
