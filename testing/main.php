<?php

// https://packagist.org/packages/spatie/icalendar-generator
require_once(__DIR__ . '/../vendor/autoload.php');


$event = new Programster\iCal\Event(
    "AWS 2021",
    "Event 1",
    Programster\iCal\EventTimeConfiguration::createTimespan(time(), time() + 3600),
    "Programster Calendars",
);

print($event);