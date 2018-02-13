<?php

namespace Stereochrome\NewsletterAdmin\Template;

use Stereochrome\NewsletterAdmin\Model\Newsletter;
use yii\bootstrap\ActiveForm;

class BaseTemplate extends \yii\base\Component {

	public $editorView = null;

	public $htmlView = null;

	public $plaintextView = null;

	public $formModelClass = null;



	protected $currentModel;

	public function __construct() {

	}

	public function init() {

	}


	public function getModel(Newsletter $newsletter) {

		if(!isset($this->currentModel)) {

			$this->currentModel = \Yii::$container->get($this->formModelClass, [$newsletter]);

		} 

		return $this->currentModel;
	}


	public function renderEditor(Newsletter $newsletter, ActiveForm $form) {
		
		$model = $this->getModel($newsletter);
		$model->loadFromFields();
		return \Yii::$app->view->render($this->editorView, [
			'newsletter' => $newsletter,
			'form' => $form,
			'model' => $model,
		]);		

	}

	public function save(Newsletter $newsletter) {

		$model = $this->getModel($newsletter);

		if($model->load(\Yii::$app->request->post())) {

			return $model->save($newsletter);

		}

		return false;

	}


	public function renderHtml($newsletter) {
		
		$model = $this->getModel($newsletter);
		$model->loadFromFields();
		return \Yii::$app->view->render($this->htmlView, [
			'newsletter' => $newsletter,
			'model' => $model,
		]);		

	}

	public function renderPlaintext() {
		$model = $this->getModel($newsletter);
		$model->loadFromFields();
		return \Yii::$app->view->render($this->plaintextView, [
			'newsletter' => $newsletter,
			'model' => $model,
		]);		
	}






}