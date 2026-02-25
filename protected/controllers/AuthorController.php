<?php
declare(strict_types=1);

class AuthorController extends Controller
{
    private $authorService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->authorService = new AuthorService();
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
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('create', 'update', 'delete'),
                'roles' => array('user'),
            )
        );
    }

    public function actionIndex(): void
    {
        $query = $_GET['q'] ?? '';

        $dataProvider = $this->authorService->getAuthorList($query);

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'query' => $query,
        ));
    }

    public function actionView($id): void
    {
        $author = $this->authorService->getAuthorForView($id);

        $this->render('view', array(
            'model' => $author,
        ));
    }

    public function actionCreate(): void
    {
        $model = new Author();

        if (isset($_POST['Author'])) {
            $result = $this->authorService->createAuthor($_POST['Author']);

            if ($result['success']) {
                Yii::app()->user->setFlash('success', 'Автор успешно создан');
                $this->redirect(array('view', 'id' => $result['model']->id));
            } else {
                $model->attributes = $_POST['Author'];
                $errors = $result['errors'];
            }
        }

        $this->render('create', array(
            'model' => $model,
            'errors' => isset($errors) ? $errors : array(),
        ));
    }

    public function actionUpdate($id): void
    {
        try {
            $author = $this->authorService->getAuthorForView($id);

            if (isset($_POST['Author'])) {
                $result = $this->authorService->updateAuthor($id, $_POST['Author']);

                if ($result['success']) {
                    Yii::app()->user->setFlash('success', 'Автор обновлен');
                    $this->redirect(array('view', 'id' => $id));
                } else {
                    $errors = $result['errors'];
                }
            }

            $this->render('update', array(
                'model' => $author,
                'errors' => isset($errors) ? $errors : array(),
            ));

        } catch (CHttpException $e) {
            throw $e;
        }
    }

    public function actionDelete($id): void
    {
        try {
            $result = $this->authorService->deleteAuthor($id);

            if ($result['success']) {
                Yii::app()->user->setFlash('success', 'Автор удален');
            } else {
                Yii::app()->user->setFlash('error', $result['errors']);
            }

        } catch (CHttpException $e) {
            Yii::app()->user->setFlash('error', $e->getMessage());
        }

        $this->redirect(array('index'));
    }
}