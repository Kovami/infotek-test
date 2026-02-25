<?php
$this->breadcrumbs = array(
    'Книги' => array('index'),
    $model->title,
);
?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1><?php echo CHtml::encode($model->title); ?></h1>
            <?php if(!Yii::app()->user->isGuest): ?>
                <div>
                    <?php echo CHtml::link('Редактировать', array('update', 'id' => $model->id), array('class' => 'btn btn-warning')); ?>
                    <?php echo CHtml::form(array('delete', 'id' => $model->id), 'post', array('style' => 'display:inline')); ?>
                        <?php echo CHtml::hiddenField('id', $model->id); ?>
                        <?php echo CHtml::submitButton('Удалить', array(
                                'class' => 'btn btn-sm btn-danger',
                                'onclick' => 'return confirm("Удалить книгу?");'
                        )); ?>
                    <?php echo CHtml::endForm(); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?php if($model->cover_image): ?>
                        <img src="<?php echo CHtml::encode($model->cover_image); ?>" class="img-fluid rounded" alt="Обложка">
                    <?php else: ?>
                        <div class="bg-light p-5 text-center rounded">
                            <i class="fas fa-book fa-5x text-muted"></i>
                            <p class="mt-2">Нет обложки</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-8">
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">Год выпуска:</th>
                            <td><?php echo $model->year; ?></td>
                        </tr>
                        <tr>
                            <th>ISBN:</th>
                            <td><?php echo CHtml::encode($model->isbn) ?: 'Не указан'; ?></td>
                        </tr>
                        <tr>
                            <th>Авторы:</th>
                            <td>
                                <?php foreach($model->authors as $author): ?>
                                    <?php echo CHtml::link(
                                        CHtml::encode($author->full_name),
                                        array('/author/view', 'id' => $author->id),
                                        array('class' => 'badge bg-primary text-decoration-none me-1')
                                    ); ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Описание:</th>
                            <td><?php echo nl2br(CHtml::encode($model->description)); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Подписка на авторов -->
<?php if(Yii::app()->user->isGuest && !empty($model->authors)): ?>
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Подписка на новых книг автора</h5>
        </div>
        <div class="card-body">
            <p>Подпишитесь на SMS уведомления о новых книгах:</p>
            <div class="row">
                <?php foreach($model->authors as $author): ?>
                    <div class="col-md-4 mb-2">
                        <?php echo CHtml::link(
                            'Подписаться на ' . $author->full_name,
                            array('/subscription/create', 'author_id' => $author->id),
                            array('class' => 'btn btn-outline-info btn-sm w-100')
                        ); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>