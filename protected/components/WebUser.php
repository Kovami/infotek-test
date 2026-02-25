<?php
declare(strict_types=1);

class WebUser extends CWebUser
{
    private $_model = null;

    public function getModel()
    {
        if ($this->_model === null && !$this->isGuest) {
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }

    public function canEdit(): bool
    {
        return !$this->isGuest;
    }
}