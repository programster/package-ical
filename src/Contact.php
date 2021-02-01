<?php

declare(strict_types = 1);
namespace Programster\iCal;


class Contact
{
    protected string $m_email;
    protected ?string $m_name;


    public function __construct(string $email, ?string $name)
    {
        $this->m_email = $email;
        $this->m_name = $name;
    }
    

    # Accessors
    public function getEmail() : string { return $this->m_email; }
    public function getName() : ?string { return $this->m_name; }
}


