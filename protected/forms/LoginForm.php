<?php
declare(strict_types=1);

class LoginForm extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe;

    private $_identity;

    public function rules(): array
    {
        return array(
            array('username, password', 'required'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }

    public function attributeLabels(): array
    {
        return array(
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        );
    }

    public function authenticate($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);

            if (!$this->_identity->authenticate()) {
                if ($this->_identity->errorCode === UserIdentity::ERROR_USERNAME_INVALID) {
                    $this->addError('username', 'Пользователь не найден');
                } else if ($this->_identity->errorCode === UserIdentity::ERROR_PASSWORD_INVALID) {
                    $this->addError('password', 'Неверный пароль');
                } else {
                    $this->addError('username', 'Ошибка аутентификации');
                }
            }
        }
    }

    public function login(): bool
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0;
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }

        return false;
    }
}
