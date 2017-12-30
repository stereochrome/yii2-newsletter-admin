<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;


$this->title = Yii::t('newsletter-admin', 'Newsletter-Lists');
$this->params['breadcrumbs'][] = $this->title;

$mainActions = [

	['label' => Yii::t('newsletter-admin', 'Create List'), 'url' => ['create'], 'options' => ['class' => 'btn btn-md btn-primary']],

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
            'title',
            'external_id',
            'active',
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    if (extension_loaded('intl')) {
                        return Yii::t('newsletter-admin', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                    }

                    return date('Y-m-d G:i:s', $model->created_at);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    if (extension_loaded('intl')) {
                        return Yii::t('newsletter-admin', '{0, date, MMMM dd, YYYY HH:mm}', [$model->updated_at]);
                    }

                    return date('Y-m-d G:i:s', $model->updated_at);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        if($model->active == 0) {
                            return;
                        }

                        return Html::a('<span class="glyphicon glyphicon-ban-circle"></span>', $url, [
                                'title' => Yii::t('newsletter-admin', 'Deactivate this list'),
                                'data-confirm' => Yii::t(
                                        'newsletter-admin',
                                        'Are you sure you want to deactivate this newsletter list?'
                                    ),
                                    'data-method' => 'POST',
                            ]);
                    }
                ],
            ],
        ],
    ]
); ?>

<?php Pjax::end() ?>

<?php $this->endContent() ?>
