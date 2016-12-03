<?php

namespace Gsquad\PiafBundle\Service;

class GsquadGetListPiaf
{
    public function getListPiafsObserves($listObservations, $espece, $name)
    {
        $datas = [];
        $latitudes = [];
        $longitudes = [];
        $listPiafsObserves = [];

        foreach ($listObservations as $observation) {
            $piafTemp = $observation->getPiaf();

            if($espece) {
                if($observation->getLatitude() && $observation->getLongitude() && ($piafTemp->getNameVern() === $espece || $piafTemp->getNameVern() === $espece." ")) {
                    $latitudes[] = $observation->getLatitude();
                    $longitudes[] = $observation->getLongitude();
                }
            }
            else {
                if($observation->getLatitude()
                    && $observation->getLongitude()
                    && (
                        $name == null
                        ||strpos($this->removeAccents($piafTemp->getLbNom()),($this->removeAccents($name)))
                        || strpos($this->removeAccents($piafTemp->getNameVern()),($this->removeAccents($name)))
                    )
                ) {
                    $latitudes[] = $observation->getLatitude();
                    $longitudes[] = $observation->getLongitude();
                }
            }

            $posA = true;
            $posB = true;

            if($espece) {
                if($piafTemp->getNameVern() === $espece || $piafTemp->getNameVern() === $espece." ") {
                    $posA = true;
                } else {
                    $posA = false;
                }
                $posB = false;
            }

            if($name !== null && !$espece) {
                $posA = strpos($this->removeAccents($piafTemp->getLbNom()),($this->removeAccents($name)));
                $posB = strpos($this->removeAccents($piafTemp->getNameVern()),($this->removeAccents($name)));
            }

            if($posA !== false || $posB !== false) {
                if(!in_array($piafTemp, $listPiafsObserves)) {
                    $piafTemp->setNbObservations(1);
                    $listPiafsObserves[] = $piafTemp;
                } else {
                    $key = array_search($piafTemp, $listPiafsObserves);
                    $listPiafsObserves[$key]->setNbObservations($listPiafsObserves[$key]->getNbObservations() + 1);
                }
            }
        }

        usort($listPiafsObserves, function ($a, $b)
        {
            if ($a->getNbObservations() == $b->getNbObservations()) {
                return 0;
            }
            return ($a->getNbObservations() > $b->getNbObservations()) ? -1 : 1;
        });

        $datas = [$listPiafsObserves, $latitudes, $longitudes];
        return $datas;
    }
}