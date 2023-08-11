<?php
declare(strict_types=1);

namespace arkania\server;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\utils\trait\Date;
use JsonException;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class MaintenanceManager {
    use SingletonTrait;

    private Config $config;

    private Config $maintenance;

    public function __construct(
        Main $plugin
    ) {
        self::setInstance($this);
        if (!file_exists($plugin->getDataFolder() . 'player_list.txt')) {
            $plugin->saveResource('player_list.txt');
        }

        $this->config = Path::config('config', PathTypeIds::YAML());
        $this->maintenance = Path::config('player_list', PathTypeIds::TXT());
    }

    public function isInMaintenance(bool $onlyOp = false) : bool {
        $this->reloadConfigs();
        if ($onlyOp) {
            return $this->config->get('maintenance') && $this->config->get('maintenance-only-op');
        }else{
            return $this->config->get('maintenance');
        }
    }

    public function reloadConfigs() : void {
        $this->config->reload();
        $this->maintenance->reload();
    }

    /**
     * @throws JsonException
     */
    public function addPlayer(string $playerName) : void {
        if ($this->isPlayerInMaintenance($playerName)) {
            return;
        }
        $this->maintenance->set($playerName);
        $this->maintenance->save();
    }

    /**
     * @param string $playerName
     * @return void
     * @throws JsonException
     */
    public function removePlayer(string $playerName) : void {
        if (!$this->isPlayerInMaintenance($playerName)) {
            return;
        }
        $this->maintenance->remove($playerName);
        $this->maintenance->save();
    }

    public function isPlayerInMaintenance(string $playerName) : bool {
        $this->reloadConfigs();
        return $this->maintenance->exists($playerName);
    }

    /**
     * @param bool $value
     * @param bool $onlyOp
     * @return void
     * @throws JsonException
     */
    public function setMaintenance(bool $value, bool $onlyOp = false) : void {
        if ($value){
            $this->config->set('maintenance_date', Date::create()->toString());
            $this->config->save();
        }
        if ($onlyOp){
            $this->config->set('maintenance_only_op', $value);
        }
        $this->config->set('maintenance', $value);
        $this->config->save();
    }

    public function getDate() : string {
        $this->reloadConfigs();
        return $this->config->get('maintenance_date');
    }

}