<?php

namespace Gsquad\PiafBundle\Service;

class GsquadChangeCoordinates
{
    public function changeLatitudes($latitudes)
    {
        for($i = 0; $i < count($latitudes); $i++) {
            $latitudes[$i] = floor($latitudes[$i] * 100);
            $latitudes[$i] = rand ($latitudes[$i] - 32 , $latitudes[$i] + 32);
            $latitudes[$i] = $latitudes[$i] / 100;
        }

        return $latitudes;
    }

    public function changeLongitudes($longitudes)
    {
        for($i = 0; $i < count($longitudes); $i++) {
            $longitudes[$i] = floor($longitudes[$i] * 100);
            $longitudes[$i] = rand ($longitudes[$i] - 32 , $longitudes[$i] + 32);
            $longitudes[$i] = $longitudes[$i] / 100;
        }

        return $longitudes;
    }

    public function changeLatitudesAdherent($latitudes)
    {
        for($i = 0; $i < count($latitudes); $i++) {
            $latitudes[$i] = floor($latitudes[$i] * 10000);
            $latitudes[$i] = rand ($latitudes[$i] - 32 , $latitudes[$i] + 32);
            $latitudes[$i] = $latitudes[$i] / 10000;
        }

        return $latitudes;
    }

    public function changeLongitudesAdherent($longitudes)
    {
        for($i = 0; $i < count($longitudes); $i++) {
            $longitudes[$i] = floor($longitudes[$i] * 10000);
            $longitudes[$i] = rand ($longitudes[$i] - 32 , $longitudes[$i] + 32);
            $longitudes[$i] = $longitudes[$i] / 10000;
        }

        return $longitudes;
    }
}