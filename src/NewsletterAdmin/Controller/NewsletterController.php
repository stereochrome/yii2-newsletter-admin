<?php

namespace Stereochrome\NewsletterAdmin\Controller;

use Da\User\Traits\ContainerAwareTrait;
use Da\User\Validator\AjaxRequestModelValidator;
use Stereochrome\NewsletterAdmin\Query\NewsletterQuery;
use Stereochrome\NewsletterAdmin\Query\NewsletterTemplateQuery;
use Stereochrome\NewsletterAdmin\Query\NewsletterListQuery;
use Stereochrome\NewsletterAdmin\Search\NewsletterSearch;
use Stereochrome\NewsletterAdmin\Model\Newsletter;
use Stereochrome\Sendy\Model\Campaign;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;


class NewsletterController extends Controller {
	
	use ContainerAwareTrait;
	
	/**
	 * @var NewsletterQuery
	 */
	protected $newsletterQuery;

	/**
	 * @var NewsletterTemplateQuery
	 */
	protected $templateQuery;

	/**
	 * @var NewsletterListQuery
	 */
	protected $listQuery;

	/**
	 * NewsletterController constructor.
	 *
	 * @param string 	      $id
	 * @param Module          $module
	 * @param NewsletterQuery $newsletterQuery
	 * @param array           $config
	 */
	public function __construct($id, $module, NewsletterQuery $newsletterQuery, NewsletterTemplateQuery $templateQuery, NewsletterListQuery $listQuery, array $config = []) {

		$this->newsletterQuery = $newsletterQuery;
		$this->templateQuery = $templateQuery;
		$this->listQuery = $listQuery;
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
                    'send' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['newsletter_editor', 'newsletter_admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['render'],
                        'roles' => '*',
                    ],
                ],
            ],
        ];
    }

    public function actionSend($id) {

        $newsletter = $this->newsletterQuery->where(['id' => $id])->one();

        $sendyComponentName = $this->module->sendyComponentName;

        $sendy = \Yii::$app->$sendyComponentName;

        $campaign = $this->make(Campaign::class, [], [

            'html_text' => $newsletter->newsletterTemplate->getTemplateObject()->renderHtml($newsletter),
            'title' => $newsletter->title,
            'subject' => $newsletter->subject,
            'list_ids' => $newsletter->newsletterList->external_id,
        ]);

        $campaign->autoSendCampaign();

        if($sendy->campaignCreate($campaign)) {
            
            $newsletter->sent = 1;
            $newsletter->sent_at = time();
            $newsletter->save();

            Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Sending the newsletter has been started.'));
        } else {
            Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Unable to send the newsletter.'));

        }

        return $this->redirect('index');

    }


    public function actionIndex() {

    	$searchModel = $this->make(NewsletterSearch::class);
		$dataProvider = $searchModel->search(Yii::$app->request->get());
		$dataProvider->sort->defaultOrder = ["id" => SORT_DESC];
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
        /** @var Newsletter $newsletter */
        $newsletter = $this->make(Newsletter::class, [], ['scenario' => 'create']);

		$this->make(AjaxRequestModelValidator::class, [$newsletter])->validate();

		if ($newsletter->load(Yii::$app->request->post())) {
            
            if ($newsletter->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Newsletter has been created'));
                
                return $this->redirect(['update', 'id' => $newsletter->id]);
            }
            Yii::$app->session->setFlash('danger', Yii::t('newsletter-admin', 'Newsletter could not be created.'));
        }

        return $this->render('create', [
        	'newsletter' => $newsletter, 
        	'templateQuery' => $this->templateQuery,
        	'listQuery' => $this->listQuery,
        ]);
    }

    public function actionUpdate($id)
    {
        $newsletter = $this->newsletterQuery->where(['id' => $id])->one();
        $newsletter->setScenario('update');

        $this->make(AjaxRequestModelValidator::class, [$newsletter])->validate();

        if ($newsletter->load(Yii::$app->request->post())) {

        	$transaction = \Yii::$app->db->beginTransaction();

            if ($newsletter->save()) {

            	if($newsletter->newsletterTemplate->getTemplateObject()->save($newsletter)) {

                	Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Newsletter details have been updated.'));

                	$transaction->commit();
            		
                	return $this->redirect(["view", "id" => $newsletter->id]);
            	} else {

            		Yii::$app->getSession()->setFlash('danger', Yii::t('newsletter-admin', 'Unable to save newsletter details (newsletter-template failed to store).'));

            	}

            } else {
            	Yii::$app->getSession()->setFlash('danger', Yii::t('newsletter-admin', 'Unable to save newsletter details.'));

            }

            $transaction->rollback();
        }



        return $this->render('update', [
        	'newsletter' => $newsletter, 
        	'templateQuery' => $this->templateQuery,
        	'listQuery' => $this->listQuery,
        ]);
    }

    public function actionDelete($id)
    {
        /** @var Newsletter $newsletter */
        $newsletter = $this->newsletterQuery->where(['id' => $id])->one();

        if($newsletter->isSent) {
        	Yii::$app->getSession()->setFlash('error', Yii::t('newsletter-admin', 'Unable to delete newsletter: was already sent.'));
        } elseif($newsletter->delete()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Newsletter has been deleted'));

        } else {
            Yii::$app->getSession()->setFlash(
                'warning',
                Yii::t('newsletter-admin', 'Unable to delete Newsletter. Please, try again later.')
            );
        }
        

        return $this->redirect(['index']);
    }

    public function actionView($id) {
    	/** @var Newsletter $newsletter */
        $newsletter = $this->newsletterQuery->where(['id' => $id])->one();

        return $this->render("view", [
        	'newsletter' => $newsletter,
        ]);
    }



    public function actionRender($id) {
    	/** @var Newsletter $newsletter */
        $newsletter = $this->newsletterQuery->where(['id' => $id])->one();

        if(!$newsletter->isSent) {

        	$user = \Yii::$app->user;

        	if($user->isGuest or (!$user->can('newsletter_editor') and !$user->can('newsletter_admin'))) {

        		throw new \yii\web\HttpException(404);

        	}
        }

        $this->layout = null;

        return $newsletter->newsletterTemplate->getTemplateObject()->renderHtml($newsletter);

    }

}