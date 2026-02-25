<?php
declare(strict_types=1);

class Subscription extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'subscriptions';
    }

    public function rules(): array
    {
        return array(
            array('author_id, phone', 'required'),
            array('phone', 'length', 'max' => 15),
            array('phone', 'match', 'pattern' => '/^\+?[0-9]{10,15}$/'),
            array('author_id', 'unique', 'className' => 'Subscription',
                'attributeName' => 'author_id',
                'criteria' => array(
                    'condition' => 'phone=:phone',
                    'params' => array(':phone' => $this->phone)
                ),
                'message' => 'Вы уже подписаны на этого автора'),
        );
    }

    public function relations(): array
    {
        return array(
            'author' => array(self::BELONGS_TO, 'Author', 'author_id'),
        );
    }

    public function attributeLabels(): array
    {
        return array(
            'id' => 'ID',
            'author_id' => 'Автор',
            'phone' => 'Телефон',
        );
    }
}