<?php

declare(strict_types = 1);
namespace Programster\iCal;


final class AttendeeCollection extends ArrayObject
{
    public function __construct(Attendee ...$cars)
    {
        parent::__construct($cars);
    }


    public function append($value)
    {
        if ($value instanceof Attendee)
        {
            parent::append($value);
        }
        else
        {
            throw new Exception("Cannot append non Attendee to a " . __CLASS__);
        }
    }


    public function offsetSet($index, $newval)
    {
        if ($newval instanceof Attendee)
        {
            parent::offsetSet($index, $newval);
        }
        else
        {
            throw new Exception("Cannot add a non Car value to a " . __CLASS__);
        }
    }
}



