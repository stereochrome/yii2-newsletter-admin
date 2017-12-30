<?php

namespace Stereochrome\NewsletterAdmin\Search;

use Stereochrome\NewsletterAdmin\Query\NewsletterQuery;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NewsletterSearch extends Model
{
    /**
     * @var string
     */
    public $subject;
    /**
     * @var int
     */
    public $newsletter_template_id;
    /**
     * @var int
     */
    public $created_at;
    /**
     * @var int
     */
    public $created_by;
    /**
     * @var int
     */
    public $updated_at;
    /**
     * @var int
     */
    public $updated_by;
    /**
     * @var bool
     */
    public $sent;
    /**
     * @var int
     */
    public $sent_at;
    /**
     * @var int
     */
    public $sent_by;
    /**
     * @var NewsletterQuery
     */
    protected $query;

    /**
     * UserSearch constructor.
     *
     * @param UserQuery $query
     * @param array     $config
     */
    public function __construct(NewsletterQuery $query, $config = [])
    {
        $this->query = $query;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'safeFields' => [['subject', 'newsletter_template_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'sent', 'sent_at', 'sent_by'], 'safe'],
            'createdDefault' => ['created_at', 'default', 'value' => null],
            'sentDefault' => ['sent_at', 'default', 'value' => null],
            'updatedDefault' => ['updated_at', 'default', 'value' => null],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('newsletter-admin', 'ID'),
            'subject' => Yii::t('newsletter-admin', 'Subject'),
            'newsletter_template_id' => Yii::t('newsletter-admin', 'Template'),
            'sent' => Yii::t('newsletter-admin', 'Sent'),
            'sent_at' => Yii::t('newsletter-admin', 'Sent At'),
            'sent_by' => Yii::t('newsletter-admin', 'Sent By'),
            'created_at' => Yii::t('newsletter-admin', 'Created At'),
            'created_by' => Yii::t('newsletter-admin', 'Created By'),
            'updated_at' => Yii::t('newsletter-admin', 'Updated At'),
            'updated_by' => Yii::t('newsletter-admin', 'Updated By'),
        ];
    }

    /**
     * @param $params
     *
     * @throws InvalidParamException
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->query;

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        if ($this->updated_at !== null) {
            $date = strtotime($this->updated_at);
            $query->andFilterWhere(['between', 'updated_at', $date, $date + 3600 * 24]);
        }

        if ($this->sent_at !== null) {
            $date = strtotime($this->sent_at);
            $query->andFilterWhere(['between', 'sent_at', $date, $date + 3600 * 24]);
        }

        $query
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['newsletter_template_id' => $this->newsletter_template_id])
            ->andFilterWhere(['created_by' => $this->created_by])
            ->andFilterWhere(['updated_by' => $this->updated_by])
            ->andFilterWhere(['sent_by' => $this->sent_by])
            ->andFilterWhere(['sent' => $this->sent]);

        return $dataProvider;
    }
}

