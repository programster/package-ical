<?php

declare(strict_types = 1);
namespace Programster\iCal;


final class AttendeeCollection extends \ArrayObject
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
            throw new \Exception("Cannot append non Attendee to a " . __CLASS__);
        }
    }


    public function offsetSet($key, $value)
    {
        if ($value instanceof Attendee)
        {
            parent::offsetSet($key, $value);
        }
        else
        {
            throw new \Exception("Cannot add a non Attendee value to a " . __CLASS__);
        }
    }
}



