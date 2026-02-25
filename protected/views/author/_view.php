<div class="col-md-4 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <h5 class="card-title">
                <?php echo CHtml::link(
                    CHtml::encode($data->full_name),
                    array('view', 'id' => $data->id)
                ); ?>
            </h5>

            <p class="card-text">
                <span class="badge bg-info">Книг: <?php echo $data->bookCount; ?></span>
                <?php if($data->subscriptions): ?>
                    <span class="badge bg-success">Подписчиков: <?php echo count($data->subscriptions); ?></span>
                <?php endif; ?>
            </p>

            <?php if($data->books): ?>
                <p class="card-text">
                    <small class="text-muted">Последние книги:</small><br>
                    <?php
                    $books = array_slice($data->books, 0, 3);
                    foreach($books as $book): ?>
                        <?php echo CHtml::link(
                            CHtml::encode($book->title),
                            array('/book/view', 'id' => $book->id),
                            array('class' => 'badge bg-light text-dark text-decoration-none me-1')
                        ); ?>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="card-footer">
            <?php echo CHtml::link('Подробнее', array('view', 'id' => $data->id), array('class' => 'btn btn-sm btn-outline-primary')); ?>

            <?php if(!Yii::app()->user->isGuest): ?>
                <?php echo CHtml::link('Ред.', array('update', 'id' => $data->id), array('class' => 'btn btn-sm btn-warning')); ?>
                <?php if($data->bookCount === 0): ?>
                    <?php echo CHtml::form(array('delete', 'id' => $data->id), 'post', array('style' => 'display:inline')); ?>
                        <?php echo CHtml::hiddenField('id', $data->id); ?>
                        <?php echo CHtml::submitButton('Удалить', array(
                                'class' => 'btn btn-sm btn-danger',
                                'onclick' => 'return confirm("Удалить автора?");'
                        )); ?>
                    <?php echo CHtml::endForm(); ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php echo CHtml::link('Подписаться', array('/subscription/create', 'author_id' => $data->id), array(
                    'class' => 'btn btn-sm btn-info float-end'
            )); ?>
        </div>
    </div>
</div>