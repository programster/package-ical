<?php

declare(strict_types = 1);
namespace Programster\iCal;


use Programster\Collections\AbstractCollection;

final class AttendeeCollection extends AbstractCollection
{
    public function __construct(Attendee ...$attendee)
    {
        parent::__construct(Attendee::class, ...$attendee);
    }
}



