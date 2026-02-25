<?php
declare(strict_types=1);

class SubscriptionController extends Controller
{
    private SubscriptionService $subscriptionService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->subscriptionService = new SubscriptionService();
    }

    public function filters(): array
    {
        return array(
            'accessControl',
            'postOnly + delete',
        );
    }

    public function accessRules(): array
    {
        return array(
            array('allow',
                'actions' => array('create', 'subscribe'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('my', 'delete'),
                'roles' => array('user'),
            )
        );
    }

    public function actionCreate(): void
    {
        $authorId = isset($_GET['author_id']) ? (int)$_GET['author_id'] : null;
        $author = null;

        if ($authorId) {
            $author = Author::model()->findByPk($authorId);
        }

        $this->render('create', array(
            'author' => $author,
        ));
    }

    public function actionSubscribe(): void
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(400, 'Неверный запрос');
        }

        $authorId = isset($_POST['author_id']) ? (int)$_POST['author_id'] : null;
        $phone = $_POST['phone'] ?? null;

        if (!$authorId || !$phone) {
            echo CJSON::encode(array(
                'success' => false,
                'errors' => array('Все поля обязательны'),
            ));
            Yii::app()->end();
            return;
        }

        $result = $this->subscriptionService->subscribe($authorId, $phone);

        echo CJSON::encode($result);
        Yii::app()->end();
    }
}