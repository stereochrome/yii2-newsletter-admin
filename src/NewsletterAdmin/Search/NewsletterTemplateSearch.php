<?php

namespace Stereochrome\NewsletterAdmin\Search;

use Stereochrome\NewsletterAdmin\Query\NewsletterTemplateQuery;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NewsletterTemplateSearch extends Model
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var int
     */
    public $identifier;
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
     * @var NewsletterQuery
     */
    protected $query;

    /**
     * UserSearch constructor.
     *
     * @param UserQuery $query
     * @param array     $config
     */
    public function __construct(NewsletterTemplateQuery $query, $config = [])
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
            'safeFields' => [['name', 'identifier', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            'createdDefault' => ['created_at', 'default', 'value' => null],
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
            'name' => Yii::t('newsletter-admin', 'Name'),
            'identifer' => Yii::t('newsletter-admin', 'Identifier'),
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

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'identifer', $this->identifier])
            ->andFilterWhere(['created_by' => $this->created_by])
            ->andFilterWhere(['updated_by' => $this->updated_by]);

        return $dataProvider;
    }
}

