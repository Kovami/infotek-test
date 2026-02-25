<?php
declare(strict_types=1);

class AuthorRepository
{
    public function findWithBooks($id)
    {
        return Author::model()
            ->with('books')
            ->findByPk($id);
    }

    public function getList(): array
    {
        return CHtml::listData(
            Author::model()->findAll(array('order' => 'full_name')),
            'id',
            'full_name'
        );
    }

    public function findAllWithBookCount(): CActiveDataProvider
    {
        $criteria = new CDbCriteria();
        $criteria->with = array('bookCount');
        $criteria->order = 't.full_name ASC';

        return new CActiveDataProvider('Author', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    public function search($query = ''): CActiveDataProvider
    {
        $criteria = new CDbCriteria();

        if (!empty($query)) {
            $criteria->compare('full_name', $query, true);
        }

        $criteria->order = 'full_name ASC';

        return new CActiveDataProvider('Author', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    public function create($data): array
    {
        $author = new Author();
        $author->attributes = $data;

        if ($author->save()) {
            return array('success' => true, 'model' => $author);
        }

        return array('success' => false, 'errors' => $author->getErrors(), 'model' => $author);
    }

    public function update(Author $author, $data): array
    {
        $author->attributes = $data;

        if ($author->save()) {
            return array('success' => true, 'model' => $author);
        }

        return array('success' => false, 'errors' => $author->getErrors(), 'model' => $author);
    }

    public function delete(Author $author): array
    {
        if ($author->bookCount > 0) {
            return array(
                'success' => false,
                'errors' => array('Невозможно удалить автора, у которого есть книги')
            );
        }

        $transaction = Yii::app()->db->beginTransaction();

        try {
            Subscription::model()->deleteAllByAttributes(array('author_id' => $author->id));

            $result = $author->delete();

            $transaction->commit();
            return array('success' => $result);

        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function getTopByYear($year, $limit = 10): array
    {
        $sql = "
            SELECT 
                a.*,
                COUNT(b.id) as books_count
            FROM authors a
            LEFT JOIN book_author ba ON a.id = ba.author_id
            LEFT JOIN books b ON ba.book_id = b.id AND b.year = :year
            GROUP BY a.id
            HAVING books_count > 0
            ORDER BY books_count DESC, a.full_name
            LIMIT :limit
        ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':year', $year, PDO::PARAM_INT);
        $command->bindValue(':limit', $limit, PDO::PARAM_INT);

        $rows = $command->queryAll();

        $authors = array();
        foreach ($rows as $row) {
            $author = Author::model()->populateRecord($row);
            $author->books_count = $row['books_count'];
            $authors[] = $author;
        }

        return $authors;
    }

    public function getAuthorStats($authorId)
    {
        $sql = "
            SELECT 
                b.year,
                COUNT(*) as books_count
            FROM books b
            JOIN book_author ba ON b.id = ba.book_id
            WHERE ba.author_id = :author_id
            GROUP BY b.year
            ORDER BY b.year DESC
        ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':author_id', $authorId, PDO::PARAM_INT);

        return $command->queryAll();
    }
}