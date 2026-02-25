<?php
declare(strict_types=1);

class Author extends CActiveRecord
{
    public $books_count;
    public $year_books = array();

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'authors';
    }

    public function rules(): array
    {
        return array(
            array('full_name', 'required'),
            array('full_name', 'length', 'max' => 255),
            array('id, full_name, created_at', 'safe', 'on' => 'search'),
        );
    }

    public function relations(): array
    {
        return array(
            'books' => array(self::MANY_MANY, 'Book', 'book_author(book_id, author_id)'),
            'bookCount' => array(self::STAT, 'Book', 'book_author(book_id, author_id)'),
            'subscriptions' => array(self::HAS_MANY, 'Subscription', 'author_id'),
        );
    }

    public function attributeLabels(): array
    {
        return array(
            'id' => 'ID',
            'full_name' => 'ФИО',
            'created_at' => 'Дата создания',
            'bookCount' => 'Количество книг',
        );
    }

    public function search(): CActiveDataProvider
    {
        $criteria = new CDbCriteria;
        $criteria->compare('full_name', $this->full_name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'full_name ASC'),
            'pagination' => array('pageSize' => 20),
        ));
    }
}