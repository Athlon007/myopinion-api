<?php

namespace Controllers;

use Services\ReportService;
use Exception;
use Models\ReportType;
use Services\OpinionService;

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

    public function getForOpinion()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        if (!$this->checkIfTokenHolderIsAdmin($token) && !$this->checkIfTokenHolderIsModerator($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        try {
            // id is second last in url
            $url = explode('/', $_SERVER['REQUEST_URI']);
            $opinionId = $url[count($url) - 2];

            $opinionService = new OpinionService();
            $opinion = $opinionService->getOpinionById($opinionId);

            if ($opinion == null) {
                $this->respondWithError(404, "Opinion not found.");
                return;
            }
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to get reports.");
        }
    }

    public function getReportTypes()
    {
        $types = ReportType::getAllTypes();
        $this->respond($types);
    }

    public function report()
    {
        try {
            $insertedReport = $this->createObjectFromPostedJson("ReportType");
            $opinionService = new OpinionService();
            $opinion = $opinionService->getOpinionById(basename($_SERVER['REQUEST_URI']));

            if ($opinion == null) {
                $this->respondWithError(404, "Opinion not found.");
                return;
            }

            $this->service->createReport($opinion, $insertedReport);

            $countReport = $this->service->countReportsForOpinionByType($opinion, $insertedReport);

            $json = $insertedReport->jsonSerialize();
            $json['count'] = $countReport;

            $output = array(
                "opinion" => $opinion,
                "report" => $json
            );

            $this->respond($output);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to report opinion.");
        }
    }

    public function pardon()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        if (!$this->checkIfTokenHolderIsAdmin($token) && !$this->checkIfTokenHolderIsModerator($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $opinionId = basename($_SERVER['REQUEST_URI']);
        try {
            $opinionService = new OpinionService();
            $opinion = $opinionService->getOpinionById($opinionId);

            if ($opinion == null) {
                $this->respondWithError(404, "Opinion not found.");
                return;
            }

            $this->service->pardonOpinion($opinion);

            $this->respond(["message" => "Opinion $opinionId pardoned."]);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to pardon opinion.");
            return;
        }
    }
}
