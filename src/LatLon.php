<?php

declare(strict_types = 1);
namespace Programster\iCal;


class LatLon
{
    private float $m_lat;
    private float $m_long;


    public function __construct(float $latitude, float $longitude)
    {
        if ($latitude > 90 || $latitude < -90)
        {
            throw new Exception("Invalid latitude value: {$latitude}");
        }

        if ($longitude > 180 || $longitude < -180)
        {
            throw new Exception("Invalid longitude value: {$longitude}");
        }

        $this->m_lat = $latitude;
        $this->m_long = $longitude;
    }

    # Accessors
    public function getLat() : float { return $this->m_lat; }
    public function getLong() : float { return $this->m_long; }
}