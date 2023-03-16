<?php

require_once("Exceptions/ReportTypeMissingException.php");

namespace Models;

use JsonSerializable;
use Models\Exceptions\ReportTypeMissingException;

class ReportType implements JsonSerializable
{
    public const HATEFUL = [0, "Hateful or abusive content"];
    public const HARASSMENT = [1, "Harassment or bulying"];
    public const MISINFORMATION = [2, "Misinformation"];
    public const SPAM = [3, "Spam or misleading"];

    private int $value;

    public function asString(): string
    {
        return match ($this->value) {
            ReportType::HATEFUL[0] => ReportType::HATEFUL[1],
            ReportType::HARASSMENT[0] => ReportType::HARASSMENT[1],
            ReportType::MISINFORMATION[0] => ReportType::MISINFORMATION[1],
            ReportType::SPAM[0] => ReportType::SPAM[1],
            default => throw new ReportTypeMissingException("Report type with the value '$this->value' does not exist.")
        };
    }

    public function jsonSerialize(): mixed
    {
        return [
            "name" => $this->asString(),
            "value" => $this->value
        ];
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public static function initByString(string $value): ReportType
    {
        $reportType = new ReportType();
        switch ($value) {
            case ReportType::HATEFUL[1]:
                $reportType->setValue(ReportType::HATEFUL[0]);
                return $reportType;
            case ReportType::HARASSMENT[1]:
                $reportType->setValue(ReportType::HARASSMENT[0]);
                return $reportType;
            case ReportType::MISINFORMATION[1]:
                $reportType->setValue(ReportType::MISINFORMATION[0]);
                return $reportType;
            case ReportType::SPAM[1]:
                $reportType->setValue(ReportType::SPAM[0]);
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
                $reportType->setValue(ReportType::HATEFUL[0]);
                return $reportType;
            case ReportType::HARASSMENT[0]:
                $reportType->setValue(ReportType::HARASSMENT[0]);
                return $reportType;
            case ReportType::MISINFORMATION[0]:
                $reportType->setValue(ReportType::MISINFORMATION[0]);
                return $reportType;
            case ReportType::SPAM[0]:
                $reportType->setValue(ReportType::SPAM[0]);
                return $reportType;
            default:
                throw new ReportTypeMissingException("Report type by the id '$id' does not exist.");
                break;
        }

        return $reportType;
    }
}
