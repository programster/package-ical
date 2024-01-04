<?php

declare(strict_types = 1);
namespace Programster\iCal;

use DateTime;

class DateTimeCollection extends \Programster\Collections\AbstractCollection
{
    public function __construct(DateTime ...$elements)
    {
        parent::__construct(DateTime::class, ...$elements);
    }
}