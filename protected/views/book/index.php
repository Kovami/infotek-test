<?php
$this->breadcrumbs = array(
    'Книги',
);
$model = new Book();
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Каталог книг</h1>
        <?php if(!Yii::app()->user->isGuest): ?>
            <?php echo CHtml::link('Добавить книгу', array('create'), array('class' => 'btn btn-primary')); ?>
        <?php endif; ?>
    </div>

    <!-- Поиск -->
    <div class="card mb-4">
        <div class="card-body">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'method' => 'get',
                'action' => array('index'),
                'htmlOptions' => array('class' => 'row g-3'),
            )); ?>

            <div class="col-md-4">
                <?php echo $form->label($model, 'title', array('class' => 'form-label')); ?>
                <?php echo $form->textField($model, 'title', array('class' => 'form-control')); ?>
            </div>

            <div class="col-md-4">
                <?php echo $form->label($model, 'year', array('class' => 'form-label')); ?>
                <?php echo $form->textField($model, 'year', array('class' => 'form-control')); ?>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <?php echo CHtml::submitButton('Поиск', array('class' => 'btn btn-primary me-2')); ?>
                <?php echo CHtml::link('Сбросить', array('index'), array('class' => 'btn btn-secondary')); ?>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'summaryText' => 'Показано {start}-{end} из {count} книг',
    'emptyText' => 'Книг не найдено',
    'template' => '{summary}<div class="row">{items}</div>{pager}',
    'pager' => array(
        'class' => 'CLinkPager',
        'header' => '',
        'htmlOptions' => array('class' => 'pagination justify-content-center'),
    ),
)); ?>