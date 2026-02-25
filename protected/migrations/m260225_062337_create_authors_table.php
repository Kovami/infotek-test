<?php

class m260225_062337_create_authors_table extends CDbMigration
{
    public function up(): void
    {
        $this->createTable('authors', array(
            'id' => 'pk',
            'full_name' => 'VARCHAR(255) NOT NULL',
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->createIndex('idx_authors_full_name', 'authors', 'full_name');
    }

    public function down(): void
    {
        $this->dropTable('authors');
    }
}