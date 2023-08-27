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

namespace arkania\area\param;

use arkania\Main;
use JsonException;
use pocketmine\utils\Config;

class ModifyParameter {

    /** @var string */
    private string $areaName;

    /** @var array|string */
    private array|string $params;

    /** @var bool|null */
    private ?bool $value;

    /**
     * @param string $areaName
     * @param array|string $params
     * @param bool|null $value
     * @throws JsonException
     */
    public function __construct(string $areaName, array|string $params, ?bool $value = null) {
        $this->areaName = $areaName;
        $this->params = $params;
        if(is_string($params)) {
            $this->value = $value;
        } else {
            $this->value = null;
        }
        $this->modifyAreaParam();
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function modifyAreaParam() : void {
        $config = new Config(Main::getInstance()->getDataFolder() . 'area/area.yml', Config::YAML);
        if(is_array($this->params)) {
            foreach ($this->params as $param => $value) {
                $config->setNested($this->areaName . '.param.' . $param, $value);
            }
        } else if(is_string($this->params)) {
            $config->setNested($this->areaName . '.param.' . $this->params, $this->value);
        }
        $config->save();
    }

}