<?php

/*
 * This file is part of the 2amigos/yii2-newsletter-admin project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */


use yii\bootstrap\Nav;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
/**
 * @var View   $this
 * @var User   $user
 * @var string $content
 */

$this->title = Yii::t('newsletter-admin', 'Update Newsletter Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('newsletter-admin', 'Templates'), 'url' => ['index']];
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

<?= $this->render('_template', ['form' => $form, 'template' => $template]) ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('newsletter-admin', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>


<?php $this->endContent() ?>
