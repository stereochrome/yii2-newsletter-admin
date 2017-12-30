<?php

namespace Stereochrome\NewsletterAdmin\Controller;

use Da\User\Traits\ContainerAwareTrait;
use Da\User\Validator\AjaxRequestModelValidator;
use Stereochrome\NewsletterAdmin\Model\NewsletterTemplate;
use Stereochrome\NewsletterAdmin\Query\NewsletterTemplateQuery;
use Stereochrome\NewsletterAdmin\Search\NewsletterTemplateSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;


class TemplateController extends Controller {
	
	use ContainerAwareTrait;
	
	/**
	 * @var NewsletterQuery
	 */
	protected $newsletterTemplateQuery;

	/**
	 * NewsletterController constructor.
	 *
	 * @param string 	      $id
	 * @param Module          $module
	 * @param NewsletterTemplateQuery $newsletterTemplateQuery
	 * @param array           $config
	 */
	public function __construct($id, $module, NewsletterTemplateQuery $newsletterTemplateQuery, array $config = []) {

		$this->newsletterTemplateQuery = $newsletterTemplateQuery;
		parent::__construct($id, $module, $config);

	}

	/**
     * {@inheritdoc}
     */
	public function behaviors()
    {
        return [
        	'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['newsletter_admin'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex() {

    	$searchModel = $this->make(NewsletterTemplateSearch::class);
		$dataProvider = $searchModel->search(Yii::$app->request->get());

		return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );

	}

	public function actionCreate()
    {
        /** @var NewsletterTemplate $template */
        $template = $this->make(NewsletterTemplate::class, [], ['scenario' => 'create']);

		$this->make(AjaxRequestModelValidator::class, [$template])->validate();

		if ($template->load(Yii::$app->request->post())) {
            
            if ($template->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Newsletter Template has been created'));
                
                return $this->redirect(['update', 'id' => $template->id]);
            }
            Yii::$app->session->setFlash('danger', Yii::t('newsletter-admin', 'Newsletter Template could not be created.'));
        }

        return $this->render('create', ['template' => $template]);
    }

    public function actionUpdate($id)
    {
        $template = $this->newsletterTemplateQuery->where(['id' => $id])->one();
        $template->setScenario('update');

        $this->make(AjaxRequestModelValidator::class, [$template])->validate();

        if ($template->load(Yii::$app->request->post())) {

            if ($template->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Newsletter Template details have been updated'));

                return $this->refresh();
            }
        }

        return $this->render('update', ['template' => $template]);
    }

    public function actionDelete($id)
    {
        /** @var NewsletterTemplate $template */
        $template = $this->newsletterTemplateQuery->where(['id' => $id])->one();

        if ($template->delete()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Template has been deleted'));

        } else {
            Yii::$app->getSession()->setFlash(
                'warning',
                Yii::t('newsletter-admin', 'Unable to delete Template. Please, try again later.')
            );
        }
        

        return $this->redirect(['index']);
    }



}