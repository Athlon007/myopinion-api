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
        $reportType->setId(htmlspecialchars($reportType->getId()));
        $reportType->setName(htmlspecialchars($reportType->getName()));

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

    public function pardonOpinion(Opinion $opinion)
    {
        $opinionID = htmlspecialchars($opinion->getId());
        $this->repo->deleteReportsForOpinion($opinionID);
    }

    public function getForOpinion(Opinion $opinion): array
    {
        $reportTypesWithCount = array();
        $reportTypes = ReportType::getAllTypes();

        foreach ($reportTypes as $type) {
            $reportTypeObject = new ReportType();
            $reportTypeObject->setId($type['id']);
            $countForType = $this->countReportsForOpinionByType($opinion, $reportTypeObject);

            $json = $reportTypeObject->jsonSerialize();
            $json['count'] = $countForType;

            $reportTypesWithCount[] = $json;
        }

        return $reportTypesWithCount;
    }
}
