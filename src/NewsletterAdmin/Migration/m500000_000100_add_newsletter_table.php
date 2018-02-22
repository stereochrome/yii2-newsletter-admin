<?php

namespace Stereochrome\NewsletterAdmin\Migration;

use yii\db\Migration;
use Da\User\Helper\MigrationHelper;
/**
 * Class m171229_220401_add_newsletter_table
 */
class m500000_000100_add_newsletter_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%newsletter}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(255)->notNull(),
                'subject' => $this->string(1500)->notNull(),
                'newsletter_template_id' => $this->integer()->notNull(),
                'newsletter_list_id' => $this->integer()->notNull(),
                
                'sent' => $this->boolean(),
                'sent_at' => $this->integer()->notNull(),
                'sent_by' => $this->integer()->notNull(),
                
                'created_at' => $this->integer()->notNull(),
                'created_by' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'updated_by' => $this->integer()->notNull(),
            ],
            MigrationHelper::resolveTableOptions($this->db->driverName)
        );
        
        $restrict = MigrationHelper::isMicrosoftSQLServer($this->db->driverName) ? 'NO ACTION' : 'RESTRICT';

        $this->addForeignKey('fk_newsletter_newsletter_template', '{{%newsletter}}', 'newsletter_template_id', '{{%newsletter_template}}', 'id', 'CASCADE', $restrict);
        
        $this->addForeignKey('fk_newsletter_newsletter_list', '{{%newsletter}}', 'newsletter_list_id', '{{%newsletter_list}}', 'id', 'CASCADE', $restrict);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    
        $this->dropTable('{{%newsletter}}');
        return true;
    }

}
