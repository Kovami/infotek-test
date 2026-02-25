<?php
$this->breadcrumbs = array(
    'Авторы',
);
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Авторы</h1>
        <?php if(!Yii::app()->user->isGuest): ?>
            <?php echo CHtml::link('Добавить автора', array('create'), array('class' => 'btn btn-primary')); ?>
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

            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text"
                           name="q"
                           value="<?php echo CHtml::encode($query); ?>"
                           class="form-control"
                           placeholder="Поиск по имени автора...">
                    <?php echo CHtml::submitButton('Найти', array('class' => 'btn btn-primary')); ?>
                    <?php if($query): ?>
                        <?php echo CHtml::link('Сбросить', array('index'), array('class' => 'btn btn-secondary')); ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>

    <!-- Список авторов -->
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'summaryText' => 'Показано {start}-{end} из {count} авторов',
    'emptyText' => 'Авторов не найдено',
    'template' => '{summary}<div class="row">{items}</div>{pager}',
    'pager' => array(
        'class' => 'CLinkPager',
        'header' => '',
        'htmlOptions' => array('class' => 'pagination justify-content-center'),
    ),
)); ?>