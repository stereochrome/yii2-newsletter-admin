<?php

namespace Stereochrome\NewsletterAdmin\Migration;

use yii\db\Migration;
use Da\User\Helper\MigrationHelper;

/**
 * Class m171229_220407_add_newsletter_content_table
 */
class m100000_000110_add_newsletter_content_table extends Migration
{
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%newsletter_content}}',
            [
                'id' => $this->primaryKey(),
                'newsletter_id' => $this->integer()->notNull(),
                'parent_field_id' => $this->integer()->null(),

                'field' => $this->string(255)->notNull(),

                'content' => $this->string(16777216),
                
                'created_at' => $this->integer()->notNull(),
                'created_by' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'updated_by' => $this->integer()->notNull(),
            ],
            MigrationHelper::resolveTableOptions($this->db->driverName)
        );
        
        $restrict = MigrationHelper::isMicrosoftSQLServer($this->db->driverName) ? 'NO ACTION' : 'RESTRICT';

        $this->addForeignKey('fk_newsletter_id', '{{%newsletter_content}}', 'newsletter_id', '{{%newsletter}}', 'id', 'CASCADE', $restrict);
        
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    
        $this->dropTable('{{%newsletter_content}}');
        return true;
    }
}
