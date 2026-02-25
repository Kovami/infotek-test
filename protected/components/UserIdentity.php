<?php

class UserIdentity extends CUserIdentity
{
    private int $_id;
    private string $_role;

    public function authenticate(): bool
    {
        $user = User::model()->findByAttributes(array('username' => $this->username));

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $user->id;
            $this->_role = $user->role;
            $this->setState('role', $user->role);
            $this->setState('username', $user->username);
            $this->setState('email', $user->email);
            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function getRole(): string
    {
        return $this->_role;
    }
}