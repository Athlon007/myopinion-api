<?php

namespace Repositories;

use PDO;
use PDOException;
use Models\Settings;

use Services\TopicService;

use DateTime;

use function PHPSTORM_META\type;

class SettingsRepository extends Repository
{
    public function getSettings(): Settings
    {
        require_once("../services/TopicService.php");
        $stmt = $this->connection->prepare("SELECT * FROM Settings LIMIT 1");
        $stmt->execute();

        $topicService = new TopicService();
        $selectedNthTopic = null;
        $dateLastTopicSelected = null;
        $hideOpinionsWithNReports = null;
        $maxReactionsPerPage = null;

        while ($row = $stmt->fetch()) {
            $selectedNthTopic = $topicService->getNthTopic($row["selectedNthTopic"]);
            $dateLastTopicSelected = DateTime::createFromFormat("Y-m-d", $row["dateLastTopicSelected"]);
            $hideOpinionsWithNReports = $row["hideOpinionsWithNReports"];
            $maxReactionsPerPage = $row["maxReactionsPerPage"];
        }

        $settings = new Settings();
        $settings->setSelectedTopic($selectedNthTopic);
        $settings->setDateLastTopicSelected($dateLastTopicSelected);
        $settings->setHideOpinionsWithNReports($hideOpinionsWithNReports);
        $settings->setMaxReactionsPerPage($maxReactionsPerPage);

        return $settings;
    }

    public function getSelectedNthTopic(): int
    {
        $stmt = $this->connection->prepare("SELECT selectedNthTopic FROM Settings LIMIT 1");
        $stmt->execute();
        return $stmt->fetch()["selectedNthTopic"];
    }

    public function setSelectedNthTopic(int $id, string $today): void
    {
        $stmt = $this->connection->prepare("UPDATE Settings SET selectedNthTopic = :id, dateLastTopicSelected = :today");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue("today", $today);
        $stmt->execute();
    }

    public function setMaxReactionsPerPage(int $value): void
    {
        $stmt = $this->connection->prepare("UPDATE Settings SET maxReactionsPerPage = :value");
        $stmt->bindValue(":value", $value);
        $stmt->execute();
    }
}
