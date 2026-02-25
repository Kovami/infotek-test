<?php
$this->breadcrumbs = array(
    'Книги' => array('index'),
    'Добавление',
);

$this->renderPartial('_form', array(
    'model' => $model,
    'authors' => $authors,
    'errors' => isset($errors) ? $errors : array(),
));
?>