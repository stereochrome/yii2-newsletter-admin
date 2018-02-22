<?php
namespace Stereochrome\NewsletterAdmin\Migration;

use yii\db\Migration;

/**
 * Class m171229_214522_add_newsletter_admin_permissions
 */
class m500000_000000_add_newsletter_admin_permissions extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $query = new \yii\db\Query();
        $count = $query->select("name")->from('auth_item')->where('name = "newsletter_admin"')->exists();

        if(!$count) {

            $this->insert('auth_item', [
                'name' => 'newsletter_admin',
                'type' => 1, // role
                'description' => 'Newsletter-Admin',
                'created_at' => time(),
                'updated_at' => time(),
            ]);

        }

        $query = new \yii\db\Query();
        $count = $query->select("name")->from('auth_item')->where('name = "newsletter_admin"')->exists();

        if(!$count) {
            $this->insert('auth_item', [
                'name' => 'newsletter_editor',
                'type' => 1, // role
                'description' => 'Newsletter-Editor',
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('auth_item', 'name = :name', ['name' => 'newsletter_admin']);
        $this->delete('auth_item', 'name = :name', ['name' => 'newsletter_editor']);

        return true;
    }

   
}
