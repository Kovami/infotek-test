<?php
$this->breadcrumbs = array(
    'Авторы' => array('index'),
    $model->full_name,
);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1><?php echo CHtml::encode($model->full_name); ?></h1>
        <div>
            <?php if(!Yii::app()->user->isGuest): ?>
                <?php echo CHtml::link('Редактировать', array('update', 'id' => $model->id), array('class' => 'btn btn-warning')); ?>
                <?php if($model->bookCount === 0): ?>
                    <?php echo CHtml::form(array('delete', 'id' => $model->id), 'post', array('style' => 'display:inline')); ?>
                        <?php echo CHtml::hiddenField('id', $model->id); ?>
                        <?php echo CHtml::submitButton('Удалить', array(
                                'class' => 'btn btn-sm btn-danger',
                                'onclick' => 'return confirm("Удалить автора?");'
                        )); ?>
                    <?php echo CHtml::endForm(); ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php echo CHtml::link('Подписаться на SMS', array('/subscription/create', 'author_id' => $model->id), array(
                    'class' => 'btn btn-info'
            )); ?>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <p class="text-muted">Всего книг: <?php echo $model->bookCount; ?></p>

                        <?php if($model->subscriptions): ?>
                            <h3><?php echo count($model->subscriptions); ?></h3>
                            <p class="text-muted">Подписчиков</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <h5>Книги автора:</h5>

                <?php if($model->books): ?>
                    <div class="list-group">
                        <?php foreach($model->books as $book): ?>
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <?php echo CHtml::link(
                                        CHtml::encode($book->title),
                                        array('/book/view', 'id' => $book->id),
                                        array('class' => 'text-decoration-none')
                                    ); ?>
                                    <span class="badge bg-secondary ms-2"><?php echo $book->year; ?></span>
                                </div>

                                <?php if($book->isbn): ?>
                                    <small class="text-muted">ISBN: <?php echo CHtml::encode($book->isbn); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">У автора пока нет книг</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>