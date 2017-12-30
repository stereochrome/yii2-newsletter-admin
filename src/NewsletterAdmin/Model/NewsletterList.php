<?php

namespace Stereochrome\NewsletterAdmin\Model;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%newsletter_list}}".
 *
 * @property int $id
 * @property string $title
 * @property string $external_id
 * @property int $active
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property Newsletter[] $newsletters
 */
class NewsletterList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'titleRequired' => [['title'], 'required'],
            'titleLength' => [['title'], 'string', 'max' => 255],

            'activeRequired' => [['active'], 'required'],
            'activeInteger' => [['active'], 'integer'],
            
            'externalIdString' => [['external_id'], 'string'],
            'externalIdLength' => [['external_id'], 'string', 'max' => 65536],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('newsletter-admin', 'ID'),
            'title' => Yii::t('newsletter-admin', 'Title'),
            'external_id' => Yii::t('newsletter-admin', 'External ID'),
            'active' => Yii::t('newsletter-admin', 'Active'),
            'created_at' => Yii::t('newsletter-admin', 'Created At'),
            'created_by' => Yii::t('newsletter-admin', 'Created By'),
            'updated_at' => Yii::t('newsletter-admin', 'Updated At'),
            'updated_by' => Yii::t('newsletter-admin', 'Updated By'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return ArrayHelper::merge(
            parent::scenarios(),
            [
                'create' => ['title', 'external_id', 'active'],
                'update' => ['title', 'external_id', 'active'],
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletters()
    {
        return $this->hasMany(Newsletter::className(), ['newsletter_list_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \Stereochrome\NewsletterAdmin\Query\NewsletterListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \Stereochrome\NewsletterAdmin\Query\NewsletterListQuery(get_called_class());
    }

    public function delete() {

        $this->active = 0;
        return $this->save();

    }
}
