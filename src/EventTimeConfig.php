<?php

declare(strict_types = 1);
namespace Programster\iCal;


use DateTime;
use DateTimeInterface;
use DateTimeZone;

class EventTimeConfig
{
    private DateTimeInterface $m_start;
    private ?DateTimeInterface $m_end;
    private string $m_type;


    private function __construct(){}


    public static function createFullDay(
        int                 $day,
        int                 $month,
        int                 $year,
        ?DateTimeZone       $timezone,
    ) : EventTimeConfig
    {
        $config = new EventTimeConfig();
        $config->m_start = DateTime::createFromFormat("Y-m-d", "{$year}-{$month}-{$day}", $timezone);
        $config->m_end = null;
        $config->m_type = "FullDay";
        return $config;
    }


    public static function createMultiDay(
        int                 $startDay,
        int                 $startMonth,
        int                 $startYear,
        int                 $endDay,
        int                 $endMonth,
        int                 $endYear,
        ?DateTimeZone       $timezone,
    ) : EventTimeConfig
    {
        $config = new EventTimeConfig();
        $startDate = DateTime::createFromFormat("Y-m-d", "{$startYear}-{$startMonth}-{$startDay}", $timezone);
        $endDate = DateTime::createFromFormat("Y-m-d", "{$endYear}-{$endMonth}-{$endDay}", $timezone);
        $config->m_type = "MultiDay";

        if ($timezone !== null)
        {
            $startDate->setTimezone($timezone);
            $endDate->setTimezone($timezone);
        }

        $config->m_start = $startDate;
        $config->m_end = $endDate;
        return $config;
    }


    public static function createTimespan(
        int $startTimestamp,
        int $endTimestamp,
        ?DateTimeZone $timezone = null,
    ) : EventTimeConfig
    {
        $config = new EventTimeConfig();
        $config->m_type = "Timespan";
        $startDate = new DateTime();
        $startDate->setTimestamp($startTimestamp);
        $endDate = new DateTime();
        $endDate->setTimestamp($endTimestamp);

        if ($timezone !== null)
        {
            $startDate->setTimezone($timezone);
            $endDate->setTimezone($timezone);
        }

        $config->m_start = $startDate;
        $config->m_end = $endDate;
        return $config;
    }


    # Accessors
    public function getStart() : DateTimeInterface { return $this->m_start; }
    public function getEnd() : ?DateTimeInterface { return $this->m_end; }
    public function getType() : string { return $this->m_type; }
}