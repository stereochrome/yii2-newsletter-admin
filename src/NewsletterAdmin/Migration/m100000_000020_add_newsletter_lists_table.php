<?php

namespace Stereochrome\NewsletterAdmin\Migration;

use yii\db\Migration;
use Da\User\Helper\MigrationHelper;

/**
 * Class m171230_204615_add_newsletter_lists_table
 */
class m100000_000020_add_newsletter_lists_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%newsletter_list}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(255)->notNull(),
                'external_id' => $this->string(65536)->null(),

                'active' => $this->boolean()->notNull(),
                
                'created_at' => $this->integer()->notNull(),
                'created_by' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'updated_by' => $this->integer()->notNull(),
            ],
            MigrationHelper::resolveTableOptions($this->db->driverName)
        );
        
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable("{{%newsletter_list}}");
    }

}
