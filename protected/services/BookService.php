<?php
declare(strict_types=1);

class BookService
{
    private $bookRepository;
    private $subscriptionService;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
        $this->subscriptionService = new SubscriptionService();
    }

    public function getBookForView($id)
    {
        $book = $this->bookRepository->findWithAuthors($id);

        if (!$book) {
            throw new CHttpException(404, 'Книга не найдена');
        }

        return $book;
    }

    public function getBookList($params = array()): CActiveDataProvider
    {
        return $this->bookRepository->search($params);
    }

    public function createBook($data, $authorIds): array
    {
        $form = new BookForm();
        $form->attributes = $data;

        if (!$form->validate()) {
            return array(
                'success' => false,
                'errors' => $form->getErrors()
            );
        }

        $authors = Author::model()->findAllByPk($authorIds);
        if (count($authors) !== count($authorIds)) {
            return array(
                'success' => false,
                'errors' => array('author_ids' => array('Некоторые авторы не найдены'))
            );
        }

        $result = $this->bookRepository->create($data, $authorIds);

        if ($result['success']) {
            foreach ($authorIds as $authorId) {
                $this->subscriptionService->notifyAboutNewBook($authorId, $result['model']);
            }

            Yii::log("Книга создана: ID {$result['model']->id}", 'info', 'book');
        }

        return $result;
    }

    public function updateBook($id, array $data, array $authorIds)
    {
        $book = Book::model()->findByPk($id);

        if (!$book) {
            throw new CHttpException(404, 'Книга не найдена');
        }

        if (!Yii::app()->user->canEdit()) {
            throw new CHttpException(403, 'Доступ запрещен');
        }

        $form = new BookForm();
        $form->attributes = $data;
        $form->book_id = $id;

        if (!$form->validate()) {
            return array(
                'success' => false,
                'errors' => $form->getErrors()
            );
        }

        return $this->bookRepository->update($book, $data, $authorIds);
    }

    public function deleteBook($id): array
    {
        $book = Book::model()->findByPk($id);

        if (!$book) {
            throw new CHttpException(404, 'Книга не найдена');
        }

        if (!Yii::app()->user->canEdit()) {
            throw new CHttpException(403, 'Доступ запрещен');
        }

        return $this->bookRepository->delete($book);
    }
}