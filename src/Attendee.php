<?php

declare(strict_types = 1);
namespace Programster\iCal;


use Spatie\IcalendarGenerator\Enums\ParticipationStatus;

class Attendee extends Contact
{
    private ?ParticipationStatus $m_participationStatus;


    public function __construct(string $email, ?string $name, ?ParticipationStatus $participationStatus)
    {
        parent::__construct($email, $name);
        $this->m_participationStatus = $participationStatus;
    }

    # Accessors
    public function getParticipationStatus() : ParticipationStatus { return $this->m_participationStatus; }
}


