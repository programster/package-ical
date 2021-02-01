<?php

declare(strict_types = 1);
namespace Programster\iCal;


class Attendee extends Contact
{
    private ?Spatie\IcalendarGenerator\Enums\ParticipationStatus $m_participationStatus;


    public function __construct(string $email, ?string $name, ?Spatie\IcalendarGenerator\Enums\ParticipationStatus $participationStatus)
    {
        parent::__construct($email, $name);
        $this->m_participationStatus = $participationStatus;
    }

    # Accessors
    public function getParticipationStatus() : Spatie\IcalendarGenerator\Enums\ParticipationStatus { return $this->m_participationStatus; }
}


