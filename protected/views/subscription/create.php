<?php
$this->breadcrumbs = array(
    'Подписка на автора',
);
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="mb-0">Подписка на новые книги</h3>
            </div>

            <div class="card-body">
                <?php if($author): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-user"></i>
                        Вы подписываетесь на автора:
                        <strong><?php echo CHtml::encode($author->full_name); ?></strong>
                    </div>
                <?php endif; ?>

                <p class="text-muted">
                    Введите ваш номер телефона, чтобы получать SMS уведомления
                    о новых книгах автора.
                </p>

                <div id="subscription-form">
                    <div class="mb-3">
                        <label class="form-label">Номер телефона:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel"
                                   id="phone"
                                   class="form-control"
                                   placeholder="+7 (999) 123-45-67"
                                   value="">
                        </div>
                        <small class="text-muted">Формат: +7XXXXXXXXXX или 8XXXXXXXXXX</small>
                    </div>

                    <input type="hidden" id="author_id" value="<?php echo $author ? $author->id : ''; ?>">

                    <button type="button" id="subscribe-btn" class="btn btn-info w-100">
                        Подписаться
                    </button>
                </div>

                <div id="subscription-result" class="mt-3" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subscribeBtn = document.getElementById('subscribe-btn');

        if (subscribeBtn) {
            subscribeBtn.addEventListener('click', async function() {
                const phone = document.getElementById('phone').value;
                let authorId = document.getElementById('author_id').value;

                if (!phone) {
                    alert('Введите номер телефона');
                    return;
                }

                if (!authorId) {
                    authorId = prompt('Введите ID автора:');
                    if (!authorId) return;
                }

                this.disabled = true;
                this.innerHTML = 'Отправка...';

                try {
                    const formData = new FormData();
                    formData.append('author_id', authorId);
                    formData.append('phone', phone);

                    const response = await fetch('<?php echo Yii::app()->createUrl("subscription/subscribe"); ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const resultDiv = document.getElementById('subscription-result');
                    resultDiv.style.display = 'block';

                    if (response.ok) {
                        const data = await response.json();

                        if (data.success) {
                            resultDiv.innerHTML = '<div class="alert alert-success">Вы успешно подписались! При добавлении новых книг вы получите SMS.</div>';
                            document.getElementById('phone').value = '';
                        } else {
                            let errors = '';
                            if (data.errors) {
                                for (const attr in data.errors) {
                                    errors += data.errors[attr].join(', ');
                                }
                            } else {
                                errors = 'Ошибка при подписке';
                            }
                            resultDiv.innerHTML = '<div class="alert alert-danger">' + errors + '</div>';
                        }
                    } else {
                        resultDiv.innerHTML = '<div class="alert alert-danger">Ошибка сервера</div>';
                    }
                } catch (error) {
                    const resultDiv = document.getElementById('subscription-result');
                    resultDiv.style.display = 'block';
                    resultDiv.innerHTML = '<div class="alert alert-danger">Ошибка сети</div>';
                } finally {
                    this.disabled = false;
                }
            });
        }
    });
</script>