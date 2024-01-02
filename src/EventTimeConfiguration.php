<?php

declare(strict_types = 1);
namespace Programster\iCal;


class EventTimeConfiguration
{
    private \DateTimeInterface $m_start;
    private ?\DateTimeInterface $m_end;
    private ?\DateTimeInterface $m_createdAt;
    private string $m_type;


    private function __construct(){}


    public static function createFullDay(
        int $day,
        int $month,
        int $year,
        ?DateTimeZone $timezone,
        ?DateTimeInterface $createdAt = null
    ) : EventTimeConfiguration
    {
        $config = new EventTimeConfiguration();
        $config->m_start = DateTime::createFromFormat("Y-m-d", "{$year}-{$month}-{$day}", $timezone);
        $config->m_end = null;
        $config->m_createdAt = $createdAt;
        $config->m_type = "FullDay";
        return $config;
    }


    public static function createMultiDay(
        int $startDay,
        int $startMonth,
        int $startYear,
        int $endDay,
        int $endMonth,
        int $endYear,
        ?DateTimeZone $timezone,
        ?DateTimeInterface $createdAt = null
    ) : EventTimeConfiguration
    {
        $config = new EventTimeConfiguration();
        $startDate = DateTime::createFromFormat("Y-m-d", "{$startYear}-{$startMonth}-{$startDay}", $timezone);
        $endDate = DateTime::createFromFormat("Y-m-d", "{$endYear}-{$endMonth}-{$endDay}", $timezone);
        $config->m_createdAt = $createdAt;
        $config->m_type = "MultiDay";

        if ($timezone !== null)
        {
            $startDate->setTimezone($timezone);
            $endDate->setTimezone($timezone);
        }

        $config->m_start = $startDate;
        $config->m_end = $endDate;
        $config->m_isFullDay = false;
        return $config;
    }


    public static function createTimespan(
        int $startTimestamp,
        int $endTimestamp,
        ?DateTimeZone $timezone = null,
        ?DateTimeInterface $createdAt = null
    ) : EventTimeConfiguration
    {
        $config = new EventTimeConfiguration();
        $config->m_type = "Timespan";
        $config->m_isMultiDay = true;
        $config->m_createdAt = $createdAt;
        $startDate = new \DateTime();
        $startDate->setTimestamp($startTimestamp);
        $endDate = new \DateTime();
        $endDate->setTimestamp($endTimestamp);

        if ($timezone !== null)
        {
            $startDate->setTimezone($timezone);
            $endDate->setTimezone($timezone);
        }

        $config->m_start = $startDate;
        $config->m_end = $endDate;
        $config->m_isFullDay = false;
        return $config;
    }


    # Accessors
    public function getStart() : \DateTimeInterface { return $this->m_start; }
    public function getEnd() : ?\DateTimeInterface { return $this->m_end; }
    public function getCreatedAt() : ?\DateTimeInterface { return $this->m_createdAt; }
    public function getType() : string { return $this->m_type; }
}