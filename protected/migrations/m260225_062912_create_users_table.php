<?php

class m260225_062912_create_users_table extends CDbMigration
{
    public function up(): void
    {
        $this->createTable('users', array(
            'id' => 'pk',
            'username' => 'VARCHAR(255) NOT NULL UNIQUE',
            'password' => 'VARCHAR(255) NOT NULL',
            'email' => 'VARCHAR(255) NOT NULL UNIQUE',
            'role' => "VARCHAR(255) NOT NULL DEFAULT 'user'",
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->insert('users', array(
            'username' => 'user',
            'password' => password_hash('user', PASSWORD_DEFAULT),
            'email' => 'user@example.com',
            'role' => 'user'
        ));
    }

    public function down(): void
    {
        $this->dropTable('users');
    }
}