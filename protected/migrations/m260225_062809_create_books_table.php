<?php

class m260225_062809_create_books_table extends CDbMigration
{
    public function up(): void
    {
        $this->createTable('books', array(
            'id' => 'pk',
            'title' => 'VARCHAR(255) NOT NULL',
            'year' => 'INT(4) NOT NULL',
            'description' => 'TEXT NULL',
            'isbn' => 'VARCHAR(13) NULL UNIQUE',
            'cover_image' => 'VARCHAR(255) NULL',
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->createIndex('idx_books_title', 'books', 'title');
        $this->createIndex('idx_books_year', 'books', 'year');
    }

    public function down(): void
    {
        $this->dropTable('books');
    }
}