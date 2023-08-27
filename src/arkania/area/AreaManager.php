<?php
declare(strict_types=1);
/**
 *     _      ____    _  __     _      _   _   ___      _             __     __  ____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            \ \   / / |___ \
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____   \ \ / /    __) |
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____|   \ V /    / __/
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            \_/    |_____|
 *
 * @author: Julien
 * @link: https://github.com/ArkaniaStudios
 */

namespace arkania\area;

use arkania\area\positions\CreateAreaPosition;
use arkania\Main;
use arkania\player\CustomPlayer;
use JsonException;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class AreaManager {
    use SingletonTrait;

    /** @var Config */
    private Config $area;

    public function __construct(Main $core) {
        self::setInstance($this);
        if(!file_exists($core->getDataFolder() . 'area/')) {
            @mkdir($core->getDataFolder() . 'area/');
        }
        $this->area = new Config($core->getDataFolder() . 'area/area.yml', Config::YAML);

    }

    /** @var array */
    public static array $pos1;

    /** @var array */
    public static array $pos2;

    /** @var array */
    private array $adminMode;

    /**
     * @param CreateAreaPosition $areaPosition
     * @return string
     * @throws JsonException
     */
    public function createArea(CreateAreaPosition $areaPosition) : string {
        $areaPosition->initArea();
        $paramList = '';
        foreach ($areaPosition->getParam() as $param => $value) {
            if($value === false) {
                $value = 'OFF';
            } else {
                $value = 'ON';
            }
            $paramList .= $param . ' => ' . $value . PHP_EOL;
        }
        return $paramList;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function existArea(string $name) : bool {
        return $this->area->exists($name);
    }

    /**
     * @param string $name
     * @return void
     * @throws JsonException
     */
    public function delArea(string $name) : void {
        $config = $this->area;
        $config->remove($name);
        $config->save();
    }

    /**
     * @param CustomPlayer $player
     * @return void
     */
    public function setAdminMode(CustomPlayer $player) : void {
        $this->adminMode[$player->getName()] = true;
    }

    /**
     * @param CustomPlayer $player
     * @return bool
     */
    public function isAdminMode(CustomPlayer $player) : bool {
        return isset($this->adminMode[$player->getName()]);
    }

    /**
     * @param CustomPlayer $player
     * @return void
     */
    public function unsetAdminMode(CustomPlayer $player) : void {
        if(!$this->isAdminMode($player)) {
            return;
        }
        unset($this->adminMode[$player->getName()]);
    }

    /**
     * @return array
     */
    public function getAllArea() : array {
        return $this->area->getAll();
    }

    /**
     * @return void
     */
    public function reload() : void {
        $this->area = new Config(Main::getInstance()->getDataFolder() . 'area/area.yml', Config::YAML);
    }

    public function getArea(string $name) : Area {
        return new Area($name);
    }

}