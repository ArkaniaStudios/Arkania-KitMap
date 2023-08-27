<?php
declare(strict_types=1);

namespace arkania\factions;

class AllyManager {

    private Faction $faction;

    public function __construct(
        Faction $faction
    ) {
        $this->faction = $faction;
    }

    public function addAlly(Faction $faction) : void {
        $data = $this->faction->getFactionData();
        $data['allies'][] = $faction->getName();
        file_put_contents($this->faction->getConfig(), json_encode($data));
        $this->faction->ally[] = $faction->getName();
        $faction->ally[] = $this->faction->getName();
    }

    public function removeAlly(Faction $faction) : void {
        $data = $this->faction->getFactionData();
        $data['allies'] = array_diff($data['allies'], [$faction->getName()]);
        file_put_contents($this->faction->getConfig(), json_encode($data));
        $this->faction->ally = array_diff($this->faction->ally, [$faction->getName()]);
        $faction->ally = array_diff($faction->ally, [$this->faction->getName()]);
    }

    public function isAlly(?Faction $faction) : bool {
        if ($faction === null) return false;
        return in_array($faction->getName(), $this->faction->ally);
    }

    public function getAllies() : array {
        return $this->faction->ally;
    }

}