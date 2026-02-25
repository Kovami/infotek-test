<?php
declare(strict_types=1);

class ReportService
{
    private $authorRepository;

    public function __construct()
    {
        $this->authorRepository = new AuthorRepository();
    }

    public function getTopAuthorsByYear($year = null): array
    {
        if ($year === null) {
            $year = date('Y');
        }

        $authors = $this->authorRepository->getTopByYear($year, 10);

        foreach ($authors as $author) {
            $criteria = new CDbCriteria();
            $criteria->with = array('authors');
            $criteria->together = true;
            $criteria->addCondition('authors.id = :author_id');
            $criteria->addCondition('year = :year');
            $criteria->params[':author_id'] = $author->id;
            $criteria->params[':year'] = $year;

            $author->year_books = Book::model()->findAll($criteria);
        }

        return array(
            'year' => $year,
            'authors' => $authors,
            'total' => count($authors)
        );
    }

    public function getYearsStats()
    {
        $sql = "
            SELECT 
                year,
                COUNT(*) as books_count,
                COUNT(DISTINCT ba.author_id) as authors_count
            FROM books b
            LEFT JOIN book_author ba ON b.id = ba.book_id
            GROUP BY year
            ORDER BY year DESC
        ";

        $command = Yii::app()->db->createCommand($sql);
        return $command->queryAll();
    }

    public function getAuthorStats($authorId): ?array
    {
        $author = Author::model()->findByPk($authorId);

        if (!$author) {
            return null;
        }

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

        return array(
            'author' => $author,
            'years' => $command->queryAll(),
            'total_books' => $author->bookCount
        );
    }
}