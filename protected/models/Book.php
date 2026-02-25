<?php
declare(strict_types=1);

class Book extends CActiveRecord
{
    public $author_ids = array();

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'books';
    }

    public function rules(): array
    {
        return array(
            array('title, year', 'required'),
            array('year', 'numerical', 'integerOnly' => true, 'min' => 1800, 'max' => date('Y') . 1),
            array('isbn', 'unique'),
            array('isbn', 'length', 'max' => 13),
            array('title', 'length', 'max' => 255),
            array('description, cover_image', 'safe'),
            array('author_ids', 'safe'),
            array('id, title, year, isbn', 'safe', 'on' => 'search'),
        );
    }

    public function relations(): array
    {
        return array(
            'authors' => array(self::MANY_MANY, 'Author', 'book_author(author_id, book_id)'),
            'authorList' => array(self::HAS_MANY, 'BookAuthor', 'book_id'),
        );
    }

    public function attributeLabels(): array
    {
        return array(
            'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover_image' => 'Обложка',
            'author_ids' => 'Авторы',
        );
    }

    public function search(): CActiveDataProvider
    {
        $criteria = new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        $criteria->compare('year', $this->year);
        $criteria->compare('isbn', $this->isbn, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'title ASC'),
            'pagination' => array('pageSize' => 20),
        ));
    }

    protected function afterSave()
    {
        parent::afterSave();

        if (!empty($this->author_ids)) {
            BookAuthor::model()->deleteAllByAttributes(array('book_id' => $this->id));

            foreach ($this->author_ids as $authorId) {
                $link = new BookAuthor();
                $link->book_id = $this->id;
                $link->author_id = $authorId;
                $link->save();
            }
        }
    }
}