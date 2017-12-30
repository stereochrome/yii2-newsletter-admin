<?php

namespace Stereochrome\NewsletterAdmin\Model;

use Stereochrome\NewsletterAdmin\Query\NewsletterQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%newsletter}}".
 *
 * @property int $id
 * @property string $subject
 * @property int $newsletter_template_id
 * @property int $newsletter_list_id
 * @property int $sent
 * @property int $sent_at
 * @property int $sent_by
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property NewsletterTemplate $newsletterTemplate
 * @property NewsletterContent[] $newsletterContents
 */
class Newsletter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter}}';
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('newsletter-admin', 'ID'),
            'subject' => Yii::t('newsletter-admin', 'Subject'),
            'newsletter_template_id' => Yii::t('newsletter-admin', 'Template'),
            'newsletter_list_id' => Yii::t('newsletter-admin', 'List'),
            'sent' => Yii::t('newsletter-admin', 'Sent'),
            'sent_at' => Yii::t('newsletter-admin', 'Sent At'),
            'sent_by' => Yii::t('newsletter-admin', 'Sent By'),
            'created_at' => Yii::t('newsletter-admin', 'Created At'),
            'created_by' => Yii::t('newsletter-admin', 'Created By'),
            'updated_at' => Yii::t('newsletter-admin', 'Updated At'),
            'updated_by' => Yii::t('newsletter-admin', 'Updated By'),
        ];
    }

    public function scenarios() {

        return ArrayHelper::merge(
            parent::scenarios(),
            [
                'create' => ['subject', 'newsletter_template_id', 'newsletter_list_id'],
                'update' => ['subject', 'newsletter_list_id'],
            ]

        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'subjectRequired' => [['subject'], 'required'],
            'subjectString' => [['subject'], 'string', 'max' => 1500],

            'newsletterTemplateRequired' => [['newsletter_template_id'], 'required'],
            'newsletterTemplateInteger' => [['newsletter_template_id'], 'integer'],
            'newsletterTemplateExists' => [['newsletter_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsletterTemplate::className(), 'targetAttribute' => ['newsletter_template_id' => 'id']],

            'newsletterListRequired' => [['newsletter_list_id'], 'required'],
            'newsletterListInteger' => [['newsletter_list_id'], 'integer'],
            'newsletterListExists' => [['newsletter_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsletterList::className(), 'targetAttribute' => ['newsletter_list_id' => 'id']],

        ];
    }

    public function getIsSent() {
        return $this->sent > 0;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletterTemplate()
    {
        return $this->hasOne(NewsletterTemplate::className(), ['id' => 'newsletter_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletterList()
    {
        return $this->hasOne(NewsletterList::className(), ['id' => 'newsletter_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletterContents()
    {
        return $this->hasMany(NewsletterContent::className(), ['newsletter_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return NewsletterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsletterQuery(get_called_class());
    }
}
