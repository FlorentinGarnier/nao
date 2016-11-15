<?php

namespace Gsquad\PiafBundle\Service;

class GsquadSetHabitat
{
    public function setHabitats($results)
    {
        foreach ($results as $result) {
            $habitat = $result->getHabitat();

            switch($habitat) {
                case 1:
                    $habitat = "Marin";
                    break;
                case 2:
                    $habitat = "Eau douce";
                    break;
                case 3:
                    $habitat = "Terrestre";
                    break;
                case 4:
                    $habitat = "Marin & Eau douce";
                    break;
                case 5:
                    $habitat = "Marin & Terrestre";
                    break;
                case 6:
                    $habitat = "Eau saumâtre";
                    break;
                case 7:
                    $habitat = "Continental (Terrestre et/ou eau douce)";
                    break;
                case 8:
                    $habitat = "Continental (Terrestre et eau douce)";
                    break;
                default:
                    $habitat = "Inconnu";
            }

            $result->setHabitat($habitat);
        }

        return $results;
    }
}