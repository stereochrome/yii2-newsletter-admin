<?php

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\widgets\Menu;

$user = \Yii::$app->user;
?>

<?php

	$mainItems = [
		['label' => Yii::t('newsletter-admin', 'Newsletter'), 'url' => ['/newsletter-admin/newsletter/index']],
	];

	if($user->can('newsletter_admin')) {
		$mainItems[] = ['label' => Yii::t('newsletter-admin', 'Lists'), 'url' => ['/newsletter-admin/list/index']];
		$mainItems[] = ['label' => Yii::t('newsletter-admin', 'Templates'), 'url' => ['/newsletter-admin/template/index']];
	}

?>

<?= Nav::widget([
 	'options' => [
        'class' => 'nav-tabs mb-1',
    ],
	'items' => $mainItems,
	
])

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?= $this->title; ?></h3>
	</div>

	<div class="panel-body">

		<div class="panel-actions mb-1 text-right">
			<?php
				if(isset($mainActions) and count($mainActions) > 0) {

					foreach($mainActions as $action) {
						echo Html::a($action['label'], $action['url'], $action['options']);
					}

				}
			?>
		</div>

		<?= $content; ?>

	</div>
</div>