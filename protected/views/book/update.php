<?php
$this->breadcrumbs = array(
    'Книги' => array('index'),
    $model->title => array('view', 'id' => $model->id),
    'Редактирование',
);

$this->renderPartial('_form', array(
    'model' => $model,
    'authors' => $authors,
    'errors' => isset($errors) ? $errors : array(),
));
?>