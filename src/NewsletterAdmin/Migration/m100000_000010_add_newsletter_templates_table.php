<?php

namespace Stereochrome\NewsletterAdmin\Migration;

use yii\db\Migration;
use Da\User\Helper\MigrationHelper;

/**
 * Class m171229_220354_add_newsletter_templates_table
 */
class m100000_000010_add_newsletter_templates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%newsletter_template}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull(),
                'identifier' => $this->string(255)->notNull(),
                'created_at' => $this->integer()->notNull(),
                'created_by' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'updated_by' => $this->integer()->notNull(),
            ],
            MigrationHelper::resolveTableOptions($this->db->driverName)
        );

        $this->createIndex('idx_newsletter_template_identifier', '{{%newsletter_template}}', ['identifier'], true);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    
        $this->dropTable('{{%newsletter_template}}');
        return true;
    }

}
