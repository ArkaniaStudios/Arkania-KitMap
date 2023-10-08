<?php
declare(strict_types=1);

namespace arkania\game;

use arkania\game\task\PiniataTask;
use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\player\CustomPlayer;
use JsonException;
use pocketmine\utils\SingletonTrait;

class KothManager {
    use SingletonTrait;

    private bool $status = false;

    /** @var (string|mixed)[] */
    private array $positions = [];

    public function __construct() {
        if (!file_exists(Main::getInstance()->getDataFolder() . "koth")) {
            mkdir(Main::getInstance()->getDataFolder() . "koth");
        }
        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new PiniataTask(), 20);
    }

    public function getEventStatus() : bool {
        return $this->status;
    }

    public function setStatus(bool $status) : void {
        $this->status = $status;
    }

    public function createKothZone() : void {
        $path = Path::config('koth/infos', PathTypeIds::YAML());
        $path->set('minX', $this->positions['minX']);
        $path->set('minZ', $this->positions['minZ']);
        $path->set('maxX', $this->positions['maxX']);
        $path->set('maxZ', $this->positions['maxZ']);
        $path->save();
    }

    /**
     * @param (string|mixed)[] $pos1
     * @param (string|mixed)[] $pos2
     */
    public function setPositions(array $pos1, array $pos2) : void {
        $this->positions['minX'] = min($pos1['x'], $pos2['x']);
        $this->positions['minZ'] = min($pos1['z'], $pos2['z']);
        $this->positions['maxX'] = max($pos1['x'], $pos2['x']);
        $this->positions['maxZ'] = max($pos1['z'], $pos2['z']);
    }

    public function checkIfIsInKothZone(CustomPlayer $player) : void {
        $path = Path::config('koth/infos', PathTypeIds::YAML());
        $minX = $path->get('minX', 0);
        $minZ = $path->get('minZ', 0);
        $maxX = $path->get('maxX', 0);
        $maxZ = $path->get('maxZ', 0);
        $position = $player->getPosition();
        if ($position->getX() >= $minX && $position->getX() <= $maxX && $position->getZ() >= $minZ && $position->getZ() <= $maxZ) {
            $player->setInKothZone(true);
        } else {
            $player->setInKothZone(false);
        }
    }

    public function getPositions() : array {
        $path = Path::config('koth/infos', PathTypeIds::YAML());
        return $path->getAll();
    }


}