<?php

namespace Stereochrome\NewsletterAdmin;

class Module extends \yii\base\Module
{
	public $prefix = 'newsletter';
	
	public $routes = [
    ];
    /**
     * @var array the class map. How the container should load specific classes
     * @see Bootstrap::buildClassMap() for more details
     */
    public $classMap = [];
    /**
     * Permission Name for access to basic newsletter features
     */
    public $editorPermissionName = 'newsletter_editor';
	
    /**
     * Permission Name for access to advanced newsletter features
     */
	public $adminPermissionName = 'newsletter_admin';

    public $enableFlashMessages = true;

    public $sendyComponentName = "sendy";

    public function init() {

    } 

    public function getMainNavigation() {

    	$user = \Yii::$app->user;

    	$isNewsletterEditor = $user->can($this->editorPermissionName);
		$isNewsletterAdmin = $user->can($this->adminPermissionName);


		if($isNewsletterAdmin or $isNewsletterEditor) {
    		return ['label' => 'Newsletter', 'url' => ['/newsletter-admin/newsletter/index']];
		}

    }

}