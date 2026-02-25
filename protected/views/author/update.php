<?php
$this->breadcrumbs = array(
    'Авторы' => array('index'),
    $model->full_name => array('view', 'id' => $model->id),
    'Редактирование',
);

$this->renderPartial('_form', array(
    'model' => $model,
    'errors' => $errors ?? array(),
));