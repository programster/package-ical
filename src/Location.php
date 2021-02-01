<?php

declare(strict_types = 1);
namespace Programster\iCal;


class Location
{
    private $m_name;
    private ?string $m_address;
    private ?LatLon $m_latLon;


    public function __construct(string $name, ?string $address, ?LatLon $latLon = null)
    {
        $this->m_name = $name;
        $this->m_address = $address;
        $this->m_latLon = $latLon;
    }


    # Accessors
    public function getName() : string { return $this->m_name; }
    public function getAddress() : string { return $this->m_address; }
    public function getLatLon() : ?LatLon { return $this->m_latLon; }
}

