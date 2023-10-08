<?php
declare(strict_types=1);

namespace arkania\utils\trait;

trait CooldownTrait {

    /**
     * @param $temps
     * @return string
     */
    final public function tempsFormat($temps): string {
        $timeRestant = (int)$temps - time();
        $jours = floor(abs($timeRestant / 86400));
        $timeRestant = $timeRestant - ($jours * 86400);
        $heures = floor(abs($timeRestant / 3600));
        $timeRestant = $timeRestant - ($heures * 3600);
        $minutes = floor(abs($timeRestant / 60));
        $secondes = ceil(abs($timeRestant - $minutes * 60));

        if($jours > 0)
            $format = $jours . ' jour(s) et ' .  $heures . ' heure(s)';
        else if($heures > 0)
            $format = $heures . ' heure(s) et ' . $minutes . ' minute(s)';
        else if($minutes > 0)
            $format = $minutes . ' minute(s) et ' . $secondes . ' seconde(s)';
        else
            $format = $secondes . 'seconde(s)';
        return $format;
    }

}