<?php
declare(strict_types=1);

class ReportController extends Controller
{
    private ReportService $reportService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->reportService = new ReportService();
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
                'actions' => array('topAuthors', 'years', 'author'),
                'users' => array('*'),
            )
        );
    }

    public function actionTopAuthors(): void
    {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

        $years = $this->getAvailableYears();

        $report = $this->reportService->getTopAuthorsByYear($year);

        $this->render('top_authors', array(
            'report' => $report,
            'years' => $years,
            'selectedYear' => $year,
        ));
    }

    public function actionYears(): void
    {
        $stats = $this->reportService->getYearsStats();

        $this->render('years', array(
            'stats' => $stats,
        ));
    }

    public function actionAuthor($id): void
    {
        $stats = $this->reportService->getAuthorStats($id);

        if (!$stats) {
            throw new CHttpException(404, 'Автор не найден');
        }

        $this->render('author', array(
            'stats' => $stats,
        ));
    }

    private function getAvailableYears()
    {
        $sql = "SELECT DISTINCT year FROM books ORDER BY year DESC";
        $command = Yii::app()->db->createCommand($sql);
        return $command->queryColumn();
    }
}