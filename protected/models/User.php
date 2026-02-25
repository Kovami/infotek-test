<?php
declare(strict_types=1);

class User extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return array(
            array('username, email', 'required'),
            array('password', 'required', 'on' => 'insert'),
            array('username, email', 'unique'),
            array('email', 'email'),
            array('role', 'in', 'range' => array('user', 'admin')),
            array('username', 'length', 'min' => 3, 'max' => 128),
            array('password', 'length', 'min' => 6, 'max' => 255),

            array('id, username, email, role, created_at', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels(): array
    {
        return array(
            'id' => 'ID',
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Email',
            'role' => 'Роль',
            'created_at' => 'Дата регистрации',
        );
    }

    public function search(): CActiveDataProvider
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('role', $this->role);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'username ASC',
            ),
        ));
    }

    protected function beforeSave(): bool
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord || !empty($this->password)) {
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            }
            return true;
        }
        return false;
    }

    public function validatePassword($password): bool
    {
        return password_verify($password, $this->password);
    }
}