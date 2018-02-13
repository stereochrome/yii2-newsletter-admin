<?php

namespace Stereochrome\NewsletterAdmin\Model;

use Da\User\Traits\ContainerAwareTrait;
use Stereochrome\NewsletterAdmin\Model\Newsletter;
use Stereochrome\NewsletterAdmin\Query\NewsletterContentQuery;

class BaseTemplateModel extends \yii\base\Model {

	use ContainerAwareTrait;

	protected $newsletter;

	protected $newsletterContentQuery;

	public function __construct(Newsletter $newsletter, NewsletterContentQuery $newsletterContentQuery) {

		$this->newsletter = $newsletter;
		$this->newsletterContentQuery = $newsletterContentQuery;
	}


	public function save() {

		if($this->validate()) {
			
			$data = $this->getDataInFields();

			return $this->saveDataInFields($data);

		}

		return false;
	}

	public function getDataInFields() {

		$data = [];

		foreach($this->attributes() as $attribute) {

			$data[$attribute] = $this->$attribute;

		}

		return $data;
	}

	public function saveDataInFields(&$data) {

		$fields = $this->loadFields();

		foreach($fields as $name => $field) {

			if(isset($data[$name])) {
				$field->content = $data[$name];
				$field->save();
			} else {
				$field->delete();
			}

			unset($data[$name]);

		}

		foreach($data as $name => $value) {

			$field = $this->make(NewsletterContent::class);

			$field->newsletter_id = $this->newsletter->id;
			$field->field = $name;
			$field->content = $value;
			if(!$field->save()) {
				return false;
			}

		}

		return true;

	}

	public function loadFields() {
		return $this->newsletterContentQuery->andWhere(["newsletter_id" => $this->newsletter->id])->indexBy('field')->all();
	}

	public function loadFromFields() {

		$data = [];
		$fields = $this->newsletterContentQuery->andWhere(["newsletter_id" => $this->newsletter->id])->all();

		foreach($fields as $field) {

			$name = $field->field;

			$this->$name = $field->content;

		}

	}


}