<?php

namespace Stereochrome\NewsletterAdmin\Controller;

use Da\User\Traits\ContainerAwareTrait;
use Stereochrome\NewsletterAdmin\Query\NewsletterQuery;
use Stereochrome\NewsletterAdmin\Search\NewsletterSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;


class NewsletterController extends Controller {
	
	use ContainerAwareTrait;
	
	/**
	 * @var NewsletterQuery
	 */
	protected $newsletterQuery;

	/**
	 * NewsletterController constructor.
	 *
	 * @param string 	      $id
	 * @param Module          $module
	 * @param NewsletterQuery $newsletterQuery
	 * @param array           $config
	 */
	public function __construct($id, $module, NewsletterQuery $newsletterQuery, array $config = []) {

		$this->newsletterQuery = $newsletterQuery;
		parent::__construct($id, $module, $config);

	}

	/**
     * {@inheritdoc}
     */
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['newsletter_editor', 'newsletter_admin'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex() {

    	$searchModel = $this->make(NewsletterSearch::class);
		$dataProvider = $searchModel->search(Yii::$app->request->get());

		return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );

	}

}