<?php

/**
 * @var yii\widgets\ActiveForm
 * @var \Stereochrome\NewsletterAdmin\Model\NewsletterList    $list
 */
?>

<?= $form->field($list, 'title')->textInput(['maxlength' => 255]) ?>
<?= $form->field($list, 'external_id')->textInput(['maxlength' => 255]) ?>
<?= $form->field($list, 'active')->dropDownList([0 => 'Deactivated', 1 => 'Activated']); ?>
