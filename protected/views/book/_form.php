<div class="card">
    <div class="card-header">
        <h3><?php echo $this->action->id == 'create' ? 'Добавить книгу' : 'Редактировать книгу'; ?></h3>
    </div>

    <div class="card-body">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'book-form',
            'enableAjaxValidation' => false,
            'htmlOptions' => array('class' => 'form-horizontal'),
        )); ?>

        <?php if(isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach($errors as $attribute => $errorMessages): ?>
                        <?php foreach($errorMessages as $message): ?>
                            <li><?php echo $message; ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <?php echo $form->labelEx($model, 'title', array('class' => 'form-label')); ?>
            <?php echo $form->textField($model, 'title', array('class' => 'form-control', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'title', array('class' => 'text-danger')); ?>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <?php echo $form->labelEx($model, 'year', array('class' => 'form-label')); ?>
                <?php echo $form->numberField($model, 'year', array('class' => 'form-control', 'min' => 1800, 'max' => date('Y') + 1)); ?>
                <?php echo $form->error($model, 'year', array('class' => 'text-danger')); ?>
            </div>

            <div class="col-md-6 mb-3">
                <?php echo $form->labelEx($model, 'isbn', array('class' => 'form-label')); ?>
                <?php echo $form->textField($model, 'isbn', array('class' => 'form-control', 'size' => 13, 'maxlength' => 13)); ?>
                <?php echo $form->error($model, 'isbn', array('class' => 'text-danger')); ?>
            </div>
        </div>

        <div class="mb-3">
            <?php echo $form->labelEx($model, 'cover_image', array('class' => 'form-label')); ?>
            <?php echo $form->urlField($model, 'cover_image', array('class' => 'form-control', 'placeholder' => 'https://...')); ?>
            <?php echo $form->error($model, 'cover_image', array('class' => 'text-danger')); ?>
        </div>

        <div class="mb-3">
            <?php echo $form->labelEx($model, 'description', array('class' => 'form-label')); ?>
            <?php echo $form->textArea($model, 'description', array('class' => 'form-control', 'rows' => 6)); ?>
            <?php echo $form->error($model, 'description', array('class' => 'text-danger')); ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Авторы</label>
            <div class="row">
                <?php foreach($authors as $author): ?>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox"
                                   name="author_ids[]"
                                   value="<?php echo $author->id; ?>"
                                   class="form-check-input"
                                   id="author_<?php echo $author->id; ?>"
                                <?php echo in_array($author->id, $model->author_ids) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="author_<?php echo $author->id; ?>">
                                <?php echo CHtml::encode($author->full_name); ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-4">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class' => 'btn btn-primary')); ?>
            <?php echo CHtml::link('Отмена', array('index'), array('class' => 'btn btn-secondary')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>