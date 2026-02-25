<?php
declare(strict_types=1);

class BookRepository
{
    public function findWithAuthors($id)
    {
        return Book::model()
            ->with('authors')
            ->findByPk($id);
    }

    public function findAllWithAuthors($pageSize = 20): CActiveDataProvider
    {
        $criteria = new CDbCriteria();
        $criteria->with = array('authors');
        $criteria->order = 't.created_at DESC';

        return new CActiveDataProvider('Book', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ),
        ));
    }

    public function search($params = array()): CActiveDataProvider
    {
        $criteria = new CDbCriteria();
        $criteria->with = array('authors');

        if (!empty($params['title'])) {
            $criteria->compare('t.title', $params['title'], true);
        }

        if (!empty($params['year'])) {
            $criteria->compare('t.year', $params['year']);
        }

        if (!empty($params['author_id'])) {
            $criteria->join = 'LEFT JOIN book_author ba ON t.id = ba.book_id';
            $criteria->compare('ba.author_id', $params['author_id']);
        }

        $criteria->order = 't.created_at DESC';

        return new CActiveDataProvider('Book', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    public function create(array $data, array $authorIds): array
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $book = new Book();
            $book->attributes = $data;

            if (!$book->save()) {
                $transaction->rollback();
                return array('success' => false, 'errors' => $book->getErrors(), 'model' => $book);
            }

            foreach ($authorIds as $authorId) {
                $ba = new BookAuthor();
                $ba->book_id = $book->id;
                $ba->author_id = $authorId;

                if (!$ba->save()) {
                    $transaction->rollback();
                    return array('success' => false, 'errors' => $ba->getErrors(), 'model' => $book);
                }
            }

            $transaction->commit();
            return array('success' => true, 'model' => $book);

        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function update(Book $book, array $data, array $authorIds): array
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $book->attributes = $data;

            if (!$book->save()) {
                $transaction->rollback();
                return array('success' => false, 'errors' => $book->getErrors(), 'model' => $book);
            }

            BookAuthor::model()->deleteAllByAttributes(array('book_id' => $book->id));

            foreach ($authorIds as $authorId) {
                $ba = new BookAuthor();
                $ba->book_id = $book->id;
                $ba->author_id = $authorId;

                if (!$ba->save()) {
                    $transaction->rollback();
                    return array('success' => false, 'errors' => $ba->getErrors(), 'model' => $book);
                }
            }

            $transaction->commit();
            return array('success' => true, 'model' => $book);

        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function delete(Book $book): array
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $result = $book->delete();
            $transaction->commit();

            return array('success' => $result);

        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function getLatest($limit = 10)
    {
        $criteria = new CDbCriteria();
        $criteria->with = array('authors');
        $criteria->order = 't.created_at DESC';
        $criteria->limit = $limit;

        return Book::model()->findAll($criteria);
    }
}