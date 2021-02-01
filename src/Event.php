<?php

declare(strict_types = 1);
namespace Programster\iCal;


class Event
{
    private \Spatie\IcalendarGenerator\Components\Calendar $m_calendar;



    /**
     *
     * @param string $calendarName
     * @param string $prodId - This property specifies the identifier for the product that created the iCalendar object.
     * @param string $eventName
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     */
    public function __construct(
        string $calendarName,
        string $eventName,
        EventTimeConfiguration $timeConfiguration,
        string $prodId,
        string $description = null,
        Location $location = null,
        ?Contact $organizer = null,
        ?AttendeeCollection $attendees = null,
        ?\Spatie\IcalendarGenerator\Enums\EventStatus $status = null,
        ?\Spatie\IcalendarGenerator\Enums\Classification $classification = null
    )
    {
        $this->m_calendar = new \Spatie\IcalendarGenerator\Components\Calendar($calendarName);
        $this->m_calendar->productIdentifier($prodId);
        $event = \Spatie\IcalendarGenerator\Components\Event::create($eventName);

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
                $event->startsAt($timeConfiguration->getEnd());
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
                $event->perid($timeConfiguration->getStart(), $timeConfiguration->getend(), false);
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