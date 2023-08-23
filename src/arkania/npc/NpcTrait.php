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

namespace arkania\npc;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\Server;

trait NpcTrait {

    /** @var string */
    private string $name = '';

    /** @var array */
    private array $commands = [];

    /** @var float */
    private float $taille = 1.0;

    /** @var string */
    private string $identifier = '';

    /** @var array */
    private array $inventaire = [];

    /** @var float */
    private float $pitch = 0.0;

    /** @var float */
    private float $yaw = 0.0;

    /** @var bool */
    private bool $isNpc = true;

    /* Setter & Getter */
    /**
     * @param string $value
     * @return void
     */
    public function setName(string $value) : void {
        $this->name = $value;
    }

    /**
     * @return string
     */
    public function getCustomName() : string {
        return $this->name;
    }

    /**
     * @param array $value
     * @return void
     */
    public function setCommands(array $value) : void {
        $this->commands = $value;
    }

    /**
     * @return array
     */
    public function getCommands() : array {
        return $this->commands;
    }

    /**
     * @param string $command
     * @return bool
     */
    public function hasCommands(string $command) : bool {
        foreach ($this->commands as $type => $commands) {
            foreach ($commands as $key => $cmd) {
                if($cmd === $command) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param float $value
     * @return void
     */
    public function setTaille(float $value) : void {
        $this->taille = $value;
    }

    /**
     * @return float
     */
    public function getTaille() : float {
        return $this->taille;
    }

    /**
     * @return string|null
     */
    public function getIdentifier() : ?string {
        return $this->identifier;
    }

    /**
     * @return array
     */
    public function getEntityInventory() : array {
        return $this->inventaire;
    }

    /**
     * @param array $value
     * @return void
     */
    public function setEntityInventory(array $value) : void {
        $this->inventaire = $value;
    }

    /**
     * @param float $pitch
     */
    public function setPitch(float $pitch) : void {
        $this->pitch = $pitch;
    }

    /**
     * @return float
     */
    public function getPitch() : float {
        return $this->pitch;
    }

    /**
     * @return float
     */
    public function getYaw() : float {
        return $this->yaw;
    }

    /**
     * @param float $yaw
     */
    public function setYaw(float $yaw) : void {
        $this->yaw = $yaw;
    }

    /**
     * @param int $type
     * @param string $command
     * @return void
     */
    public function addCommand(int $type, string $command) : void {
        $this->commands[$type][] = $command;
    }

    /**
     * @param string $command
     * @return void
     */
    public function removeCommand(string $command) : void {
        foreach ($this->commands as $type => $commands) {
            foreach ($commands as $key => $cmd) {
                if($cmd === $command) {
                    unset($this->commands[$type][$key]);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function listCommands() : string {
        $list = '';
        foreach ($this->commands as $type => $commands) {
            foreach ($commands as $key => $cmd) {
                $list .= "\n" . '§f- §e' . $cmd . "\n";
            }
        }
        return $list;
    }

    /**
     * @return bool
     */
    public function isNpc() : bool {
        return $this->isNpc;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setNpc(bool $value = true) : void {
        $this->isNpc = $value;
    }

    /**
     * @param CompoundTag $compoundTag
     * @return CompoundTag
     */
    public function saveNpcData(CompoundTag $compoundTag) : CompoundTag {
        $compoundTag->setString(NpcDataIds::ENTITY_NAME, $this->getCustomName());
        $compoundTag->setFloat(NpcDataIds::ENTITY_SIZE, $this->getTaille());
        $compoundTag->setFloat(NpcDataIds::ENTITY_PITCH, $this->getPitch());
        $compoundTag->setFloat(NpcDataIds::ENTITY_YAW, $this->getYaw());
        $compoundTag->setString(NpcDataIds::ENTITY_ID, $this->getIdentifier());
        $compoundTag->setString(NpcDataIds::ENTITY_COMMAND, serialize($this->getCommands()));
        $compoundTag->setString(NpcDataIds::ENTITY_INVENTAIRE, serialize($this->getEntityInventory()));
        $compoundTag->setString(NpcDataIds::ENTITY_NPC, $this->isNpc()? 'true' : 'false');
        return $compoundTag;
    }

    /**
     * @param CompoundTag $compoundTag
     * @return void
     */
    public function restorNpcData(CompoundTag $compoundTag) : void {
        $this->setNpc();
        $this->setName($compoundTag->getString(NpcDataIds::ENTITY_NAME));
        $this->setTaille($compoundTag->getFloat(NpcDataIds::ENTITY_SIZE));
        $this->setPitch($compoundTag->getFloat(NpcDataIds::ENTITY_PITCH));
        $this->setYaw($compoundTag->getFloat(NpcDataIds::ENTITY_YAW));
        $this->setCommands(unserialize($compoundTag->getString(NpcDataIds::ENTITY_COMMAND, 'a:0:{}')));
        $this->setEntityInventory(unserialize($compoundTag->getString(NpcDataIds::ENTITY_INVENTAIRE, 'a:0:{}')));
    }

    /**
     * @param Player $player
     * @return void
     */
    public function executeCommand(Player $player) : void {
        $playersCommands = $this->getCommands()[0] ?? [];
        $serverCommands = $this->getCommands()[1] ?? [];
        $serverInstance = Server::getInstance();
        foreach ($playersCommands as $command) {
            $serverInstance->dispatchCommand($player, $command);
        }
        foreach ($serverCommands as $command) {
            $serverInstance->dispatchCommand(new ConsoleCommandSender($serverInstance, $serverInstance->getLanguage()), str_replace('{PLAYER}', $player->getName(), $command));
        }
    }

}