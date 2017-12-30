<?php

/**
 * @var yii\widgets\ActiveForm
 * @var \Stereochrome\NewsletterAdmin\Model\NewsletterTemplate    $template
 */
?>

<?= $form->field($template, 'name')->textInput(['maxlength' => 255]) ?>
<?= $form->field($template, 'identifier')->textInput(['maxlength' => 255]) ?>
