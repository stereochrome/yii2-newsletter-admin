<?php
namespace Stereochrome\NewsletterAdmin;

use Da\User\Controller\SecurityController;
use Da\User\Model\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\web\Application as WebApplication;
use yii\console\Application as ConsoleApplication;
use yii\base\Application;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface {

    public $moduleId = "newsletter-admin";


	public function bootstrap($app) {

    	if ($app->hasModule($this->moduleId) && $app->getModule($this->moduleId) instanceof Module) {

            $map = $this->buildClassMap($app->getModule($this->moduleId)->classMap);
            $this->initContainer($app, $map);
			$this->initTranslations($app);
			

			if ($app instanceof WebApplication) {
				$this->initControllerNamespace($app);
				$this->initUrlRoutes($app);
				$this->initEvents($app);
			} else {

			}
		}
		
	}

	protected function initEvents() {

		
	}

	protected function initUrlRoutes(WebApplication $app)
    {
        $module = $app->getModule($this->moduleId);
        $config = [
            'class' => 'yii\web\GroupUrlRule',
            'prefix' => $module->prefix,
            'rules' => $module->routes,
        ];

        if ($module->prefix !== 'newsletter') {
            $config['routePrefix'] = 'newsletter';
        }

        $rule = Yii::createObject($config);
        $app->getUrlManager()->addRules([$rule], false);
    }
	
	protected function initControllerNamespace(WebApplication $app)
    {
        $app->getModule($this->moduleId)->controllerNamespace = 'Stereochrome\NewsletterAdmin\Controller';
        $app->getModule($this->moduleId)->setViewPath('@Stereochrome/NewsletterAdmin/resources/views');
    }

    protected function initTranslations(Application $app)
    {
        if (!isset($app->get('i18n')->translations['newsletter-admin*'])) {
            $app->get('i18n')->translations['newsletter-admin*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/resources/i18n',
                'sourceLanguage' => 'en-US',
            ];
        }
    }

    protected function initContainer($app, $map)
    {
        $di = Yii::$container;
        try {

            // events
            //$di->set(Event\UserEvent::class);

            // class map models + query classes
            $modelClassMap = [];
            foreach ($map as $class => $definition) {
                $di->set($class, $definition);
                $model = is_array($definition) ? $definition['class'] : $definition;
                $name = (substr($class, strrpos($class, '\\') + 1));
                $modelClassMap[$class] = $model;
                if (in_array($name, ['Newsletter', 'NewsletterContent', 'NewsletterTemplate', 'NewsletterList'])) {
                    $di->set(
                        "Stereochrome\\NewsletterAdmin\\Query\\{$name}Query",
                        function () use ($model) {
                            return $model::find();
                        }
                    );
                }
            }


        } catch (Exception $e) {
            die($e);
        }
    }

    /**
     * Builds class map according to user configuration.
     *
     * @param array $userClassMap user configuration on the module
     *
     * @throws Exception
     * @return array
     */
    protected function buildClassMap(array $userClassMap)
    {
        $map = [];

        $defaults = [
            // --- models
            'Newsletter' => 'Stereochrome\NewsletterAdmin\Model\Newsletter',
            'NewsletterTemplate' => 'Stereochrome\NewsletterAdmin\Model\NewsletterTemplate',
            'NewsletterList' => 'Stereochrome\NewsletterAdmin\Model\NewsletterList',
            'NewsletterContent' => 'Stereochrome\NewsletterAdmin\Model\NewsletterContent',
            
            // --- search
            'NewsletterSearch' => 'Stereochrome\NewsletterAdmin\Search\NewsletterSearch',
            'NewsletterTemplateSearch' => 'Stereochrome\NewsletterAdmin\Search\NewsletterTemplateSearch',
            'NewsletterListSearch' => 'Stereochrome\NewsletterAdmin\Search\NewsletterListSearch',

            // --- forms
            'NewsletterForm' => 'Stereochrome\NewsletterAdmin\Form\NewsletterForm',
            'NewsletterTemplateForm' => 'Stereochrome\NewsletterAdmin\Form\NewsletterTemplateForm',
            
        ];

        $routes = [
            'Stereochrome\NewsletterAdmin\Model' => [
                'Newsletter',
                'NewsletterTemplate',
                'NewsletterList',
                'NewsletterContent',
            ],
            'Stereochrome\NewsletterAdmin\Search' => [
                'NewsletterSearch',
                'NewsletterTemplateSearch',
                'NewsletterListSearch',
            ],
            'Stereochrome\NewsletterAdmin\Form' => [
                'NewsletterForm',
                'NewsletterTemplateForm',
            ],
        ];

        $mapping = array_merge($defaults, $userClassMap);

        foreach ($mapping as $name => $definition) {
            $map[$this->getRoute($routes, $name) . "\\$name"] = $definition;
        }

        return $map;
    }

    /**
     * Returns the parent class name route of a short class name.
     *
     * @param array  $routes class name routes
     * @param string $name
     *
     * @throws Exception
     * @return int|string
     *
     */
    protected function getRoute(array $routes, $name)
    {
        foreach ($routes as $route => $names) {
            if (in_array($name, $names, false)) {
                return $route;
            }
        }
        throw new Exception("Unknown configuration class name '{$name}'");
    }
}
