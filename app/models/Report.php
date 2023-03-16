<?php

namespace Models;

use Models\Opinion;
use Models\ReportType;
use JsonSerializable;

class Report implements JsonSerializable
{
    private int $id;
    private Opinion $opinion;
    private ReportType $reportType;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): void
    {
        $this->id = $value;
    }

    public function getOpinion(): Opinion
    {
        return $this->opinion;
    }

    public function setOpinion(Opinion $opinion): void
    {
        $this->opinion = $opinion;
    }

    public function getReportType(): ReportType
    {
        return $this->reportType;
    }

    public function setReportType(ReportType $value): void
    {
        $this->reportType = $value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->getId(),
            "opinion" => $this->getOpinion(),
            "report_type" => $this->getReportType()
        ];
    }
}
