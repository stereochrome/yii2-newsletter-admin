<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\helpers\Html;

/**
 * @var yii\web\View        $this
 * @var \Da\User\Model\User $user
 */

$this->title = Yii::t('newsletter-admin', 'Create a Newsletter List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('newsletter-admin', 'Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$mainActions = [

]


?>

<?php $this->beginContent('@Stereochrome/NewsletterAdmin/resources/views/shared/admin_layout.php', ['mainActions' => $mainActions]) ?>


<div class="clearfix"></div>
<?= $this->render(
    '@Da/User/resources/views/shared/_alert',
    [
        'module' => Yii::$app->getModule('newsletter-admin'),
    ]
) ?>

<?php $form = ActiveForm::begin(
    [
        'layout' => 'horizontal',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]
); ?>

<?= $this->render('_list', ['form' => $form, 'list' => $list]) ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(
            Yii::t('newsletter-admin', 'Save'),
            ['class' => 'btn btn-block btn-success']
        ) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>


<?php $this->endContent() ?>
