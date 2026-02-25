<?php
declare(strict_types=1);

class AuthorForm extends CFormModel
{
    public $author_id;
    public $full_name;

    public function rules(): array
    {
        return array(
            array('full_name', 'required'),
            array('full_name', 'length', 'min' => 2, 'max' => 255),
            array('full_name', 'match', 'pattern' => '/^[а-яА-Яa-zA-Z\s\-\.]+$/u',
                'message' => 'Имя может содержать только буквы, пробелы, дефисы и точки'),
            array('full_name', 'uniqueName'),
        );
    }

    public function uniqueName($attribute, $params): void
    {
        $criteria = new CDbCriteria();
        $criteria->compare('full_name', $this->full_name);

        if (isset($this->author_id)) {
            $criteria->addCondition('id != :id');
            $criteria->params[':id'] = $this->author_id;
        }

        $exists = Author::model()->exists($criteria);

        if ($exists) {
            $this->addError($attribute, 'Автор с таким именем уже существует');
        }
    }

    public function attributeLabels(): array
    {
        return array(
            'full_name' => 'ФИО автора',
        );
    }
}