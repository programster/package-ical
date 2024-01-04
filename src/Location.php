<?php

declare(strict_types = 1);
namespace Programster\iCal;


class Location
{
    private string $m_name;
    private ?string $m_address;
    private ?LatLon $m_latLon;


    /**
     * Create a location for an event.
     * @param string $name - a human-understandable name for the location. E.g. "Meeting Room 1".
     * @param string|null $address - a normal address for the location. E.g. 10 Cloverfield Lane, Hahnville, New Orleans
     * @param LatLon|null $latLon - optionally provide GPS coordinates.
     */
    public function __construct(string $name, ?string $address = null, ?LatLon $latLon = null)
    {
        $this->m_name = $name;
        $this->m_address = $address;
        $this->m_latLon = $latLon;
    }


    # Accessors
    public function getName() : string { return $this->m_name; }
    public function getAddress() : ?string { return $this->m_address; }
    public function getLatLon() : ?LatLon { return $this->m_latLon; }
}

