<?php
$this->breadcrumbs = array(
    'Отчеты' => array('index'),
    'Топ авторов за год',
);
?>

<div class="card">
    <div class="card-header">
        <h1>ТОП 10 авторов по количеству книг</h1>
    </div>

    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <?php echo CHtml::beginForm(array('report/topAuthors'), 'get', array('class' => 'form-inline')); ?>
                <div class="input-group">
                    <span class="input-group-text">Год:</span>
                    <?php echo CHtml::dropDownList('year', $selectedYear, array_combine($years, $years), array(
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()'
                    )); ?>
                    <?php echo CHtml::hiddenField('r', 'report/topAuthors'); ?>
                </div>
                <?php echo CHtml::endForm(); ?>
            </div>
        </div>

        <?php if($report['authors']): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Автор</th>
                        <th>Количество книг</th>
                        <th>Книги за <?php echo $report['year']; ?> год</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    <?php foreach($report['authors'] as $author): ?>
                        <tr>
                            <td>
                                <?php if($i <= 3): ?>
                                    <span class="badge bg-warning text-dark"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <?php echo $i; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo CHtml::link(
                                    CHtml::encode($author->full_name),
                                    array('/author/view', 'id' => $author->id)
                                ); ?>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill"><?php echo $author->books_count; ?></span>
                            </td>
                            <td>
                                <?php if(isset($author->year_books) && count($author->year_books) > 0): ?>
                                    <?php foreach($author->year_books as $book): ?>
                                        <div>
                                            <?php echo CHtml::link(
                                                CHtml::encode($book->title),
                                                array('/book/view', 'id' => $book->id)
                                            ); ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">нет книг</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Нет данных за <?php echo $report['year']; ?> год
            </div>
        <?php endif; ?>
    </div>
</div>