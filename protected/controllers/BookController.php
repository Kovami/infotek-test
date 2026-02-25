<?php
declare(strict_types=1);

class BookController extends Controller
{
    private BookService $bookService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->bookService = new BookService();
    }

    public function filters(): array
    {
        return array(
            'accessControl',
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
            ),
        );
    }

    public function actionIndex(): void
    {
        $params = array();

        if (isset($_GET['title'])) {
            $params['title'] = $_GET['title'];
        }

        $dataProvider = $this->bookService->getBookList($params);

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionView($id): void
    {
        $book = $this->bookService->getBookForView($id);

        $this->render('view', array(
            'model' => $book,
        ));
    }

    public function actionCreate(): void
    {
        $authors = Author::model()->findAll(array('order' => 'full_name'));

        if (isset($_POST['Book'])) {
            $data = $_POST['Book'];
            $authorIds = $_POST['author_ids'] ?? array();

            $result = $this->bookService->createBook($data, $authorIds);

            if ($result['success']) {
                Yii::app()->user->setFlash('success', 'Книга успешно создана');
                $this->redirect(array('view', 'id' => $result['model']->id));
            } else {
                $errors = $result['errors'];
                $model = $result['model'] ?? new Book();
            }
        } else {
            $model = new Book();
            $errors = array();
        }

        $this->render('create', array(
            'model' => $model,
            'authors' => $authors,
            'errors' => $errors,
        ));
    }

    public function actionUpdate($id): void
    {
        try {
            $book = $this->bookService->getBookForView($id);
            $authors = Author::model()->findAll(array('order' => 'full_name'));

            $authorIds = CHtml::listData($book->authors, 'id', 'id');

            if (isset($_POST['Book'])) {
                $data = $_POST['Book'];
                $newAuthorIds = $_POST['author_ids'] ?? array();

                $result = $this->bookService->updateBook($id, $data, $newAuthorIds);

                if ($result['success']) {
                    Yii::app()->user->setFlash('success', 'Книга обновлена');
                    $this->redirect(array('view', 'id' => $id));
                } else {
                    $errors = $result['errors'];
                }
            }

            $this->render('update', array(
                'model' => $book,
                'authors' => $authors,
                'authorIds' => $authorIds,
                'errors' => $errors ?? array(),
            ));

        } catch (CHttpException $e) {
            throw $e;
        }
    }

    public function actionDelete($id): void
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Неверный запрос');
        }

        try {
            $this->bookService->deleteBook($id);
            Yii::app()->user->setFlash('success', 'Книга удалена');
        } catch (CHttpException $e) {
            Yii::app()->user->setFlash('error', $e->getMessage());
        }

        $this->redirect(array('index'));
    }
}