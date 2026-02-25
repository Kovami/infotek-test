<?php

class m260225_062931_create_subscriptions_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('subscriptions', array(
            'id' => 'pk',
            'author_id' => 'INT NOT NULL',
            'phone' => 'VARCHAR(15) NOT NULL',
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'UNIQUE KEY unique_author_phone (author_id, phone)',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_subscriptions_author', 'subscriptions', 'author_id', 'authors', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_subscriptions_author', 'subscriptions');
        $this->dropTable('subscriptions');
    }
}