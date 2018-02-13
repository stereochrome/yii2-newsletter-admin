<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View        $this
 * @var \Stereochrome\NewsletterAdmin\Model\Newsletter $newsletter
 * @var \Stereochrome\NewsletterAdmin\Query\TemplateQuery $templateQuery
 * @var \Stereochrome\NewsletterAdmin\Query\ListQuery $listQuery
 */

$this->title = Yii::t('newsletter-admin', 'Update a Newsletter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('newsletter-admin', 'Newsletters'), 'url' => ['index']];
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

<?= $form->field($newsletter, 'title')->textInput(['maxlength' => 255]) ?>

<?= $form->field($newsletter, 'newsletter_template_id')->dropDownList(ArrayHelper::map($templateQuery->active()->all(), 'id', 'name')) ?>

<?= $form->field($newsletter, 'newsletter_list_id')->dropDownList(ArrayHelper::map($listQuery->active()->all(), 'id', 'title')) ?>

<hr/>

<?= $form->field($newsletter, 'subject')->textInput(['maxlength' => 1500]) ?>


<?= $newsletter->newsletterTemplate->getTemplateObject()->renderEditor($newsletter, $form); ?>


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

