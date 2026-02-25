<div class="col-md-6 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <h5 class="card-title">
                <?php echo CHtml::link(
                    CHtml::encode($data->title),
                    array('view', 'id' => $data->id)
                ); ?>
            </h5>

            <h6 class="card-subtitle mb-2 text-muted">
                <?php echo $data->year; ?> год
                <?php if($data->isbn): ?>
                    <span class="badge bg-info">ISBN: <?php echo CHtml::encode($data->isbn); ?></span>
                <?php endif; ?>
            </h6>

            <p class="card-text">
                <?php
                $authors = array();
                foreach($data->authors as $author) {
                    $authors[] = CHtml::link(
                        CHtml::encode($author->full_name),
                        array('author/view', 'id' => $author->id)
                    );
                }
                echo implode(', ', $authors);
                ?>
            </p>

            <?php if($data->description): ?>
                <p class="card-text">
                    <?php echo CHtml::encode(substr($data->description, 0, 100)) . '...'; ?>
                </p>
            <?php endif; ?>
        </div>

        <?php if(!Yii::app()->user->isGuest): ?>
            <div class="card-footer">
                <?php echo CHtml::link('Редактировать', array('update', 'id' => $data->id), array('class' => 'btn btn-sm btn-warning')); ?>
                <?php echo CHtml::form(array('delete', 'id' => $data->id), 'post', array('style' => 'display:inline')); ?>
                    <?php echo CHtml::hiddenField('id', $data->id); ?>
                    <?php echo CHtml::submitButton('Удалить', array(
                            'class' => 'btn btn-sm btn-danger',
                            'onclick' => 'return confirm("Удалить книгу?");'
                    )); ?>
                <?php echo CHtml::endForm(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>