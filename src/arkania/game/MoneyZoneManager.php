<?php
declare(strict_types=1);

namespace arkania\game;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\player\CustomPlayer;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;

class MoneyZoneManager {
    use SingletonTrait;

    /** @var (string|mixed)[] */
    private array $positions = [];

    public function __construct() {
        if (!file_exists(Main::getInstance()->getDataFolder() . "moneyzone")) {
            mkdir(Main::getInstance()->getDataFolder() . "moneyzone");
        }
    }

    public function createMoneyZone() : void {
        $path = Path::config('moneyzone/infos', PathTypeIds::YAML());
        $path->set('minX', $this->positions['minX']);
        $path->set('minZ', $this->positions['minZ']);
        $path->set('maxX', $this->positions['maxX']);
        $path->set('maxZ', $this->positions['maxZ']);
        $path->save();
    }

    /**
     * @param (string|mixed)[] $pos1
     * @param (string|mixed)[] $pos2
     * @return void
     */
    public function setPositions(array $pos1, array $pos2) : void {
        $this->positions['minX'] = min($pos1['x'], $pos2['x']);
        $this->positions['minZ'] = min($pos1['z'], $pos2['z']);
        $this->positions['maxX'] = max($pos1['x'], $pos2['x']);
        $this->positions['maxZ'] = max($pos1['z'], $pos2['z']);
    }

    public function checkIfIsInMoneyZone(CustomPlayer $player) : void {
        $path = Path::config('moneyzone/infos', PathTypeIds::YAML());
        $minX = $path->get('minX');
        $minZ = $path->get('minZ');
        $maxX = $path->get('maxX');
        $maxZ = $path->get('maxZ');
        $position = $player->getPosition();
        if ($position->getX() >= $minX && $position->getX() <= $maxX && $position->getZ() >= $minZ && $position->getZ() <= $maxZ) {
            $player->setInMoneyZone(true);
        } else {
            $player->setInMoneyZone(false);
        }
    }

}