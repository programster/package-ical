<?php

declare(strict_types = 1);
namespace Programster\iCal;


use Brick\DateTime\Instant;
use Brick\DateTime\LocalDateTime;
use DateTime;
use Exception;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Enums\Classification;
use Spatie\IcalendarGenerator\Enums\EventStatus;

class Event
{
    private Calendar $m_calendar;


    /**
     * Create a calendar event.
     * @param string $calendarName
     * @param string $eventName - the name for the event (this will be the main string that appears in the calendar).
     * @param EventTimeConfig $timeConfiguration - a configuration for when the event starts/stops.
     * @param string $prodId - This property specifies the identifier for the product that created the iCalendar object.
     * @param string|null $description - an optional description for the event.
     * @param string|null $uuid - optionally provide a unique ID for the event. Required if you wish to be able to
     * create a new ical file later for updating it in someone's calendar.
     * @param Location|null $location - optionally provide details about where the event is held.
     * @param Contact|null $organizer - specify details of whom the organizer is.
     * @param AttendeeCollection|null $attendees - optionally specify who will be attending the event.
     * @param EventStatus|null $status - optionally specify the status of the event. E.g. it might have been cancelled.
     * @param Classification|null $classification - optionally specify whether the event is confidential etc.
     * @param RecurConfig|null $recurConfig - specify if the event is recurring. E.g. recurs every Monday.
     * @param DateTimeCollection|null $repeatsOn - optionally specify a set of dates that the event repeats on.
     * This will likely be used instead of, rather than in addition to, the recurConfig.
     * @param LocalDateTime|Instant|null $createdAt - when ical event was created. This is particularly useful to ensure
     * that the latest version of this event is overrides all previous versions. E.g. the order in which the user
     * adds the event to their calendar will not matter, because the latest date will always be latest and not be
     * overridden by a previous version of the ical file.
     * @throws Exception
     */
    public function __construct(
        string $calendarName,
        string $eventName,
        EventTimeConfig $timeConfiguration,
        string $prodId,
        string $description = null,
        string $uuid = null,
        Location $location = null,
        ?Contact $organizer = null,
        ?AttendeeCollection $attendees = null,
        ?EventStatus $status = null,
        ?Classification $classification = null,
        RecurConfig $recurConfig = null,
        DateTimeCollection $repeatsOn = null,
        LocalDateTime|Instant|null $createdAt = null,
    )
    {
        $this->m_calendar = new Calendar($calendarName);
        $this->m_calendar->productIdentifier($prodId);
        $event = \Spatie\IcalendarGenerator\Components\Event::create($eventName);

        if ($uuid !== null)
        {
            $event->uniqueIdentifier($uuid);
        }

        if ($createdAt === null)
        {
            // default to current unix timestamp if createdAt not provided.
            $event->createdAt(new DateTime());
        }
        else
        {
            if ($createdAt instanceof LocalDateTime)
            {
                $event->createdAt($createdAt->toNativeDateTime());
            }
            elseif ($createdAt instanceof Instant)
            {
                $dateTime = new DateTime();
                $dateTime->setTimestamp($createdAt->getEpochSecond());
                $event->createdAt($dateTime);
            }
            else
            {
                throw new Exception("Unhandled created at type: " . get_class($createdAt));
            }
        }

        if ($description !== null)
        {
            $event->description($description);
        }

        if ($location !== null)
        {
            $event->addressName($location->getName());

            if ($location->getAddress() !== null)
            {
                $event->address($location->getAddress());
            }

            if ($location->getLatLon() !== null)
            {
                $event->coordinates($location->getLatLon()->getLat(), $location->getLatLon()->getLong());
            }
        }

        if ($recurConfig !== null)
        {
            $event->rrule($recurConfig->recurrenceRule);

            if ($recurConfig->doNotRepeatOn !== null && count($recurConfig->doNotRepeatOn) > 0)
            {
                $event->doNotRepeatOn($recurConfig->doNotRepeatOn->getArrayCopy());
            }
        }

        if ($repeatsOn !== null && count($repeatsOn) > 0)
        {
            $event->repeatOn($repeatsOn->getArrayCopy());
        }

        if ($status !== null)
        {
            $event->status($status);
        }

        if ($classification !== null)
        {
            $event->classification($classification);
        }

        switch ($timeConfiguration->getType())
        {
            case 'Timespan':
            {
                $event->startsAt($timeConfiguration->getStart());
                $event->endsAt($timeConfiguration->getEnd());
            }
            break;

            case 'FullDay':
            {
                $event->startsAt($timeConfiguration->getStart());
                $event->fullDay();
            }
            break;

            case 'MultiDay':
            {
                $event->period($timeConfiguration->getStart(), $timeConfiguration->getend(), false);
            }
            break;

            default:
            {
                throw new Exception("Unrecognized time config type.");
            }
        }

        if ($organizer !== null)
        {
            $event->organizer($organizer->getEmail(), $organizer->getName());
        }

        if ($attendees !== null && count($attendees) > 0)
        {
            foreach ($attendees as $attendee)
            {
                /* @var $attendee Attendee */
                $event->attendee($attendee->getEmail(), $attendee->getName(), $attendee->getParticipationStatus());
            }
        }

        $this->m_calendar->event($event);
    }


    public function __toString()
    {
        return $this->m_calendar->get();
    }
}
