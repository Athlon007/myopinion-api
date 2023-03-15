<?php

namespace Services;

use Repositories\ReportRepository;
use Models\Opinion;
use Models\ReportType;

class ReportService
{
    private ReportRepository $repo;

    public function __construct()
    {
        $this->repo = new ReportRepository();
    }

    public function createReport(Opinion $opinion, ReportType $reportType): void
    {
        $this->repo->createReport($opinion, $reportType);
    }

    public function getOpinionsWithReports(): array
    {
        return $this->repo->getOpinionsWithReports();
    }

    public function countReportsForOpinionByType(Opinion $opinion, ReportType $reportType): int
    {
        return $this->repo->countReportsForOpinionByType($opinion, $reportType);
    }

    public function countReportsForOpinion(Opinion $opinion): int
    {
        return $this->repo->countReportsForOpinion($opinion);
    }

    public function deleteReportsForOpinion(int $opinionID)
    {
        $opinionID = htmlspecialchars($opinionID);
        $this->repo->deleteReportsForOpinion($opinionID);
    }
}
