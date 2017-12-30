<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;


$this->title = Yii::t('newsletter-admin', 'Newsletter');
$this->params['breadcrumbs'][] = $this->title;

$mainActions = [

	['label' => Yii::t('newsletter-admin', 'Create Newsletter'), 'url' => ['create'], 'options' => ['class' => 'btn btn-md btn-primary']],

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
            'subject',
            [
            	'attribute' => 'newsletter_template_id',
            	'value' => function($model) {
            		return $model->newsletterTemplate->name;
            	},
            	'filter' => false,
            ],
            [
                'header' => Yii::t('newsletter-admin', 'Sent'),
                'value' => function ($model) {
                    if ($model->isSent) {
                        return '<div class="text-center">
                                <span class="text-success">' . Yii::t('newsletter-admin', 'Sent') . '</span>
                            </div>';
                    }

                    return 
	
					Html::a(
                        Yii::t('newsletter-admin', 'Send-Test'),
                        ['send', 'id' => $model->id],
                        [
                            'class' => 'btn btn-xs btn-info',
                            'data-method' => 'post',
                        ]
                    ).
					"&nbsp;".
                    Html::a(
                        Yii::t('newsletter-admin', 'Send'),
                        ['send', 'id' => $model->id],
                        [
                            'class' => 'btn btn-xs btn-danger',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('newsletter-admin', 'Are you sure you want to send this newsletter?'),
                        ]
                    );
                },
                'format' => 'raw',
            ],
           [
                'attribute' => 'sent_at',
                'value' => function ($model) {
                	if($model->sent_at > 0) {

	                    if (extension_loaded('intl')) {
	                        return Yii::t('newsletter-admin', '{0, date, MMMM dd, YYYY HH:mm}', [$model->sent_at]);
	                    }

	                    return date('Y-m-d G:i:s', $model->sent_at);
                	}
                },
            ],
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
            ],
        ],
    ]
); ?>

<?php Pjax::end() ?>

<?php $this->endContent() ?>
