<?php

namespace Stereochrome\NewsletterAdmin\Model;

use Stereochrome\NewsletterAdmin\Query\NewsletterTemplateQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%newsletter_template}}".
 *
 * @property int $id
 * @property string $name
 * @property string $identifier
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property Newsletter[] $newsletters
 */
class NewsletterTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter_template}}';
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
            'name' => Yii::t('newsletter-admin', 'Name'),
            'identifier' => Yii::t('newsletter-admin', 'Identifier'),
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
                'create' => ['name', 'identifier'],
                'update' => ['name', 'identifier'],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'nameRequired' => [['name'], 'required'],
            'nameLength' => [['name'], 'string', 'max' => 255],

            'identifierRequired' => [['identifier'], 'required'],
            'identifierLength' => [['identifier'], 'string', 'max' => 255],
            'identifierUnique' => [['identifier'], 'unique'],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletters()
    {
        return $this->hasMany(Newsletter::className(), ['newsletter_template_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return NewsletterTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsletterTemplateQuery(get_called_class());
    }
}
