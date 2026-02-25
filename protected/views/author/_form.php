<div class="card">
    <div class="card-header">
        <h3><?php echo $this->action->id == 'create' ? 'Добавить автора' : 'Редактировать автора'; ?></h3>
    </div>

    <div class="card-body">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'author-form',
            'enableAjaxValidation' => false,
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
            <?php echo $form->labelEx($model, 'full_name', array('class' => 'form-label')); ?>
            <?php echo $form->textField($model, 'full_name', array(
                'class' => 'form-control',
                'size' => 60,
                'maxlength' => 255,
                'placeholder' => 'Иванов Иван Иванович'
            )); ?>
            <?php echo $form->error($model, 'full_name', array('class' => 'text-danger')); ?>
        </div>

        <div class="mt-4">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class' => 'btn btn-primary')); ?>
            <?php echo CHtml::link('Отмена', array('index'), array('class' => 'btn btn-secondary')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>