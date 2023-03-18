<?php

namespace Controllers;

use Services\ReportService;
use Exception;
use Models\ReportType;

class ReportController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new ReportService();
    }

    public function getAll()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        // only admin can see all reports
        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        try {
            $reports = $this->service->getOpinionsWithReports();
            $reportTypes = ReportType::getAllTypes();

            $output = array();
            foreach ($reports as $report) {
                $reportTypesArrayWithCount = array();
                foreach ($reportTypes as $type) {
                    $reportTypeObject = new ReportType();
                    $reportTypeObject->setId($type['id']);
                    $countForType = $this->service->countReportsForOpinionByType($report, $reportTypeObject);

                    $json = $reportTypeObject->jsonSerialize();
                    $json['count'] = $countForType;

                    array_push($reportTypesArrayWithCount, $json);
                }

                $entry = array(
                    "opinion" => $report,
                    "reports" => $reportTypesArrayWithCount
                );

                array_push($output, $entry);
            }

            $this->respond($output);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to get reports.");
        }
    }

    public function getReportTypes()
    {
        $types = ReportType::getAllTypes();
        $this->respond($types);
    }
}
