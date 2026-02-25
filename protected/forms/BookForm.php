<?php
declare(strict_types=1);

class BookForm extends CFormModel
{
    public $book_id;
    public $title;
    public $year;
    public $description;
    public $isbn;
    public $cover_image;

    public function rules(): array
    {
        return array(
            array('title, year', 'required'),
            array('title', 'length', 'min' => 2, 'max' => 255),
            array('year', 'numerical', 'integerOnly' => true, 'min' => 1800, 'max' => date('Y') . 1),
            array('isbn', 'length', 'max' => 13),
            array('isbn', 'uniqueIsbn'),
            array('description', 'length', 'max' => 5000),
            array('cover_image', 'url'),
        );
    }

    public function uniqueIsbn($attribute, $params): void
    {
        if (!empty($this->isbn)) {
            $criteria = new CDbCriteria();
            $criteria->compare('isbn', $this->isbn);

            if (!empty($this->book_id)) {
                $criteria->addCondition('id != :id');
                $criteria->params[':id'] = $this->book_id;
            }

            $exists = Book::model()->exists($criteria);

            if ($exists) {
                $this->addError($attribute, 'Книга с таким ISBN уже существует');
            }
        }
    }

    public function attributeLabels(): array
    {
        return array(
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover_image' => 'Обложка',
        );
    }
}