<?php
$this->breadcrumbs = array(
    'Авторы' => array('index'),
    'Добавление',
);

$this->renderPartial('_form', array(
    'model' => $model,
    'errors' => $errors ?? array(),
));