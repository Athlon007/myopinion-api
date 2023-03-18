<?php

namespace Models;

use JsonSerializable;
use Models\Exceptions\ReportTypeMissingException;

class ReportType implements JsonSerializable
{
    public const HATEFUL = [0, "Hateful or abusive content"];
    public const HARASSMENT = [1, "Harassment or bulying"];
    public const MISINFORMATION = [2, "Misinformation"];
    public const SPAM = [3, "Spam or misleading"];

    private int $id;
    private string $name;

    public function asString(): string
    {
        return match ($this->id) {
            ReportType::HATEFUL[0] => ReportType::HATEFUL[1],
            ReportType::HARASSMENT[0] => ReportType::HARASSMENT[1],
            ReportType::MISINFORMATION[0] => ReportType::MISINFORMATION[1],
            ReportType::SPAM[0] => ReportType::SPAM[1],
            default => throw new ReportTypeMissingException("Report type with the value '$this->id' does not exist.")
        };
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "name" => $this->asString()
        ];
    }

    public function setId(int $value): void
    {
        $this->id = $value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public static function initByString(string $value): ReportType
    {
        $reportType = new ReportType();
        switch ($value) {
            case ReportType::HATEFUL[1]:
                $reportType->getId(ReportType::HATEFUL[0]);
                return $reportType;
            case ReportType::HARASSMENT[1]:
                $reportType->getId(ReportType::HARASSMENT[0]);
                return $reportType;
            case ReportType::MISINFORMATION[1]:
                $reportType->getId(ReportType::MISINFORMATION[0]);
                return $reportType;
            case ReportType::SPAM[1]:
                $reportType->getId(ReportType::SPAM[0]);
                return $reportType;
            default:
                throw new ReportTypeMissingException("Report type by the name '$value' does not exist.");
                break;
        }

        return $reportType;
    }

    public static function initByInt(int $id): ReportType
    {
        $reportType = new ReportType();

        switch ($id) {
            case ReportType::HATEFUL[0]:
                $reportType->setId(ReportType::HATEFUL[0]);
                return $reportType;
            case ReportType::HARASSMENT[0]:
                $reportType->setId(ReportType::HARASSMENT[0]);
                return $reportType;
            case ReportType::MISINFORMATION[0]:
                $reportType->setId(ReportType::MISINFORMATION[0]);
                return $reportType;
            case ReportType::SPAM[0]:
                $reportType->setId(ReportType::SPAM[0]);
                return $reportType;
            default:
                throw new ReportTypeMissingException("Report type by the id '$id' does not exist.");
                break;
        }

        return $reportType;
    }

    public static function getAllTypes(): array
    {
        // bind all to id, name pattern.
        return [
            [
                "id" => ReportType::HATEFUL[0],
                "name" => ReportType::HATEFUL[1]
            ],
            [
                "id" => ReportType::HARASSMENT[0],
                "name" => ReportType::HARASSMENT[1]
            ],
            [
                "id" => ReportType::MISINFORMATION[0],
                "name" => ReportType::MISINFORMATION[1]
            ],
            [
                "id" => ReportType::SPAM[0],
                "name" => ReportType::SPAM[1]
            ]
        ];
    }
}
