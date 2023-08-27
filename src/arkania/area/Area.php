<?php
declare(strict_types=1);

namespace arkania\area;

use arkania\Main;
use pocketmine\utils\Config;

class Area {

    private Config $config;
    private string $areaName;

    public function __construct(
        string $areaName
    ) {
        $this->areaName = $areaName;
        $this->config = new Config(Main::getInstance()->getDataFolder() . 'area/area.yml', Config::YAML);
    }

    public function canPvp() : bool {
        return $this->config->getNested($this->areaName . '.param.pvp');
    }

    public function canPlace() : bool {
        return $this->config->getNested($this->areaName . '.param.canPlaceBlock');
    }

    public function canBreak() : bool {
        return $this->config->getNested($this->areaName . '.param.canBreakBlock');
    }

    public function canUseCommand() : bool {
        return $this->config->getNested($this->areaName . '.param.canUseCommand');
    }

    public function canDropItem() : bool {
        return $this->config->getNested($this->areaName . '.param.canDropItem');
    }

    public function canClaim() : bool {
        return $this->config->getNested($this->areaName . '.param.canClaim');
    }

    public function setCanPvp(bool $value) : void {
        $this->config->setNested($this->areaName . '.param.pvp', $value);
        $this->config->save();
    }

    public function setCanPlace(bool $value) : void {
        $this->config->setNested($this->areaName . '.param.canPlaceBlock', $value);
        $this->config->save();
    }

    public function setCanBreak(bool $value) : void {
        $this->config->setNested($this->areaName . '.param.canBreakBlock', $value);
        $this->config->save();
    }

    public function setCanUseCommand(bool $value) : void {
        $this->config->setNested($this->areaName . '.param.canUseCommand', $value);
        $this->config->save();
    }

    public function setCanDropItem(bool $value) : void {
        $this->config->setNested($this->areaName . '.param.canDropItem', $value);
        $this->config->save();
    }

    public function setCanClaim(bool $value) : void {
        $this->config->setNested($this->areaName . '.param.canClaim', $value);
        $this->config->save();
    }

}