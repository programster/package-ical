<?php

/*
 * A class responsible for how the event repeats itself. E.g. weekly going forward, except next tuesday.
 */

namespace Programster\iCal;

use Spatie\IcalendarGenerator\ValueObjects\RRule;

class RecurConfig
{
    /**
     * Create a configuration for how an event recurs.
     * @param RRule $recurrenceRule - provide the rule for specifying how the event repeats. E.g. daily, weekly etc.
     * Refer to: https://github.com/spatie/icalendar-generator#recurrence-rules for how to create.
     * @param ?\DateTimeCollection|null $doNotRepeatOn - optionally specify dates that are excluded from the
     * recurrance. E.g. if recurrance set to weekly on Friday, one may wish to skip Friday the 13th.
     */
    public function __construct(public RRule $recurrenceRule, public ?DateTimeCollection $doNotRepeatOn = null)
    {

    }
}