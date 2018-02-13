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
    ['label' => Yii::t('newsletter-admin', 'Update'), 'url' => ['update', 'id' => $newsletter->id]],
    ['label' => Yii::t('newsletter-admin', 'Send Test'), 'url' => ['send-test', 'id' => $newsletter->id]],
    ['label' => Yii::t('newsletter-admin', 'Send'), 'url' => ['send', 'id' => $newsletter->id]],
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

<iframe width="100%" height="650" src="<?= \yii\helpers\Url::to(['/newsletter-admin/newsletter/render', 'id' => $newsletter->id]); ?>"></iframe>

<?php $this->endContent() ?>

