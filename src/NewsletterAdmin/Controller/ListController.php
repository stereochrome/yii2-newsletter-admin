<?php

namespace Stereochrome\NewsletterAdmin\Controller;

use Da\User\Traits\ContainerAwareTrait;
use Da\User\Validator\AjaxRequestModelValidator;
use Stereochrome\NewsletterAdmin\Model\NewsletterList;
use Stereochrome\NewsletterAdmin\Query\NewsletterListQuery;
use Stereochrome\NewsletterAdmin\Search\NewsletterListSearch;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;


class ListController extends Controller {
	
	use ContainerAwareTrait;
	
	/**
	 * @var NewsletterQuery
	 */
	protected $newsletterListQuery;

	/**
	 * NewsletterController constructor.
	 *
	 * @param string 	      $id
	 * @param Module          $module
	 * @param NewsletterListQuery $newsletterListQuery
	 * @param array           $config
	 */
	public function __construct($id, $module, NewsletterListQuery $newsletterListQuery, array $config = []) {

		$this->newsletterListQuery = $newsletterListQuery;
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

    	$searchModel = $this->make(NewsletterListSearch::class);
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
        /** @var NewsletterList $list */
        $list = $this->make(NewsletterList::class, [], ['scenario' => 'create']);

		$this->make(AjaxRequestModelValidator::class, [$list])->validate();

		if ($list->load(Yii::$app->request->post())) {
            
            if ($list->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Newsletter List has been created'));
                
                return $this->redirect(['update', 'id' => $list->id]);
            }
            Yii::$app->session->setFlash('danger', Yii::t('newsletter-admin', 'Newsletter List could not be created.'));
        }

        return $this->render('create', ['list' => $list]);
    }

    public function actionUpdate($id)
    {
        $list = $this->newsletterListQuery->where(['id' => $id])->one();
        $list->setScenario('update');

        $this->make(AjaxRequestModelValidator::class, [$list])->validate();

        if ($list->load(Yii::$app->request->post())) {

            if ($list->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'Newsletter List details have been updated'));

                return $this->refresh();
            }
        }

        return $this->render('update', ['list' => $list]);
    }

    public function actionDelete($id)
    {
        /** @var List $list */
        $list = $this->newsletterListQuery->where(['id' => $id])->one();

        if ($list->delete()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('newsletter-admin', 'List has been deleted'));

        } else {
            Yii::$app->getSession()->setFlash(
                'warning',
                Yii::t('newsletter-admin', 'Unable to delete List. Please, try again later.')
            );
        }
        

        return $this->redirect(['index']);
    }


}