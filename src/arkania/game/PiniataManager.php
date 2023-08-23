<?php
declare(strict_types=1);

namespace arkania\game;

use arkania\game\task\PiniataTask;
use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use JsonException;
use pocketmine\utils\SingletonTrait;

class PiniataManager {
    use SingletonTrait;

    /** @var (string|mixed)[] */
    private array $positions = [];

    public function __construct() {
        if (!file_exists(Main::getInstance()->getDataFolder() . "piniata")) {
            mkdir(Main::getInstance()->getDataFolder() . "piniata");
        }
        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new PiniataTask(), 20);
    }

    /**
     * @throws JsonException
     */
    public function createSpawnLama() : void {
        $path = Path::config('piniata/infos', PathTypeIds::YAML());
        $path->set('x', $this->positions['x']);
        $path->set('y', $this->positions['y']);
        $path->set('z', $this->positions['z']);
        $path->save();
    }

    /**
     * @param (string|mixed)[] $pos
     * @return void
     */
    public function setPositions(array $pos) : void {
        $this->positions['x'] = $pos['x'];
        $this->positions['y'] = $pos['y'];
        $this->positions['z'] = $pos['z'];
    }

    public function getPositions() : array {
        $path = Path::config('piniata/infos', PathTypeIds::YAML());
        return $path->getAll();
    }

}