<?php
declare(strict_types=1);

class AuthorService
{
    private $authorRepository;

    public function __construct()
    {
        $this->authorRepository = new AuthorRepository();
    }

    public function getAuthorForView($id)
    {
        $author = $this->authorRepository->findWithBooks($id);

        if (!$author) {
            throw new CHttpException(404, 'Автор не найден');
        }

        return $author;
    }

    public function getAuthorList($query = ''): CActiveDataProvider
    {
        return $this->authorRepository->search($query);
    }

    public function createAuthor(array $data): array
    {
        $form = new AuthorForm();
        $form->attributes = $data;

        if (!$form->validate()) {
            return array(
                'success' => false,
                'errors' => $form->getErrors()
            );
        }

        $result = $this->authorRepository->create($data);

        if ($result['success']) {
            Yii::log("Автор создан: ID {$result['model']->id}", 'info', 'author');
        }

        return $result;
    }

    public function updateAuthor($id, array $data): array
    {
        $author = Author::model()->findByPk($id);

        if (!$author) {
            throw new CHttpException(404, 'Автор не найден');
        }

        if (!Yii::app()->user->canEdit()) {
            throw new CHttpException(403, 'Доступ запрещен');
        }

        $form = new AuthorForm();
        $form->attributes = $data;
        $form->author_id = $id;

        if (!$form->validate()) {
            return array(
                'success' => false,
                'errors' => $form->getErrors()
            );
        }

        return $this->authorRepository->update($author, $data);
    }

    public function deleteAuthor($id): array
    {
        $author = Author::model()->findByPk($id);

        if (!$author) {
            throw new CHttpException(404, 'Автор не найден');
        }

        if (!Yii::app()->user->canEdit()) {
            throw new CHttpException(403, 'Доступ запрещен');
        }

        if ($author->bookCount > 0) {
            return array(
                'success' => false,
                'errors' => array('Невозможно удалить автора, у которого есть книги. Сначала удалите книги автора.')
            );
        }

        return $this->authorRepository->delete($author);
    }
}