<?php
declare(strict_types=1);

class BookAuthor extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'book_author';
    }

    public function rules(): array
    {
        return array(
            array('book_id, author_id', 'required'),
            array('book_id, author_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function relations(): array
    {
        return array(
            'book' => array(self::BELONGS_TO, 'Book', 'book_id'),
            'author' => array(self::BELONGS_TO, 'Author', 'author_id'),
        );
    }
}