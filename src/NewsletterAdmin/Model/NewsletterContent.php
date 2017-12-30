<?php

namespace Stereochrome\NewsletterAdmin\Model;

use Stereochrome\NewsletterAdmin\Query\NewsletterContentQuery;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%newsletter_content}}".
 *
 * @property int $id
 * @property int $newsletter_id
 * @property int $parent_field_id
 * @property string $field
 * @property string $content
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property Newsletter $newsletter
 */
class NewsletterContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter_content}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('newsletter-admin', 'ID'),
            'newsletter_id' => Yii::t('newsletter-admin', 'Newsletter'),
            'parent_field_id' => Yii::t('newsletter-admin', 'Parent Field'),
            'field' => Yii::t('newsletter-admin', 'Field'),
            'content' => Yii::t('newsletter-admin', 'Content'),
            'created_at' => Yii::t('newsletter-admin', 'Created At'),
            'created_by' => Yii::t('newsletter-admin', 'Created By'),
            'updated_at' => Yii::t('newsletter-admin', 'Updated At'),
            'updated_by' => Yii::t('newsletter-admin', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletter()
    {
        return $this->hasOne(Newsletter::className(), ['id' => 'newsletter_id']);
    }

    /**
     * @inheritdoc
     * @return NewsletterContentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsletterContentQuery(get_called_class());
    }
}
