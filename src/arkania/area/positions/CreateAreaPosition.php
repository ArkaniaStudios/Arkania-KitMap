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

namespace arkania\area\positions;

use arkania\Main;
use JsonException;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;

class CreateAreaPosition {

    /** @var string */
    private string $areaName = '';

    /** @var Vector3 */
    private Vector3 $position;

    /** @var array */
    private array $param = [];

    /** @var Vector3 */
    private Vector3 $position2;

    public function __construct(string $areaName, Vector3 $positon, Vector3 $position2, array $param = []) {
        $this->areaName = $areaName;
        $this->position = $positon;
        $this->position2 = $position2;
        $this->param = $param;
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function initArea() : void {
        $areaName = $this->areaName;
        $position = $this->position;
        $position2 = $this->position2;
        $param = $this->param;
        $config = new Config(Main::getInstance()->getDataFolder() . 'area/area.yml', Config::YAML);
        $config->setNested($areaName . '.position.min_x', $position->getX());
        $config->setNested($areaName . '.position.min_z', $position->getZ());
        $config->setNested($areaName . '.position.max_x', $position2->getX());
        $config->setNested($areaName . '.position.max_z', $position2->getZ());
        foreach ($param as $parametre => $value) {
            $config->setNested($areaName . '.param.' . $parametre, $value ?? false);
        }
        $config->save();
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->areaName;
    }

    /**
     * @return Vector3
     */
    public function getPosition() : Vector3 {
        return $this->position;
    }

    /**
     * @return Vector3
     */
    public function getPosition2() : Vector3 {
        return $this->position2;
    }

    /**
     * @return array
     */
    public function getParam() : array {
        return $this->param;
    }

}