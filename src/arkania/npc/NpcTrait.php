<?php

/*
 *
 *     _      ____    _  __     _      _   _   ___      _                 _   _   _____   _____  __        __   ___    ____    _  __
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \               | \ | | | ____| |_   _| \ \      / /  / _ \  |  _ \  | |/ /
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \     _____    |  \| | |  _|     | |    \ \ /\ / /  | | | | | |_) | | ' /
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \   |_____|   | |\  | | |___    | |     \ V  V /   | |_| | |  _ <  | . \
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            |_| \_| |_____|   |_|      \_/\_/     \___/  |_| \_\ |_|\_\
 *
 * Arkania is a Minecraft Bedrock server created in 2019,
 * we mainly use PocketMine-MP to create content for our server
 * but we use something else like WaterDog PE
 *
 * @author Arkania-Team
 * @link https://arkaniastudios.com
 *
 */

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

	private string $name = '';

	private array $commands = [];

	private float $taille = 1.0;

	private string $identifier = '';

	private array $inventaire = [];

	private float $pitch = 0.0;

	private float $yaw = 0.0;

	private bool $isNpc = true;

	/* Setter & Getter */

	public function setName(string $value) : void {
		$this->name = $value;
	}

	public function getCustomName() : string {
		return $this->name;
	}

	public function setCommands(array $value) : void {
		$this->commands = $value;
	}

	public function getCommands() : array {
		return $this->commands;
	}

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

	public function setTaille(float $value) : void {
		$this->taille = $value;
	}

	public function getTaille() : float {
		return $this->taille;
	}

	public function getIdentifier() : ?string {
		return $this->identifier;
	}

	public function getEntityInventory() : array {
		return $this->inventaire;
	}

	public function setEntityInventory(array $value) : void {
		$this->inventaire = $value;
	}

	public function setPitch(float $pitch) : void {
		$this->pitch = $pitch;
	}

	public function getPitch() : float {
		return $this->pitch;
	}

	public function getYaw() : float {
		return $this->yaw;
	}

	public function setYaw(float $yaw) : void {
		$this->yaw = $yaw;
	}

	public function addCommand(int $type, string $command) : void {
		$this->commands[$type][] = $command;
	}

	public function removeCommand(string $command) : void {
		foreach ($this->commands as $type => $commands) {
			foreach ($commands as $key => $cmd) {
				if($cmd === $command) {
					unset($this->commands[$type][$key]);
				}
			}
		}
	}

	public function listCommands() : string {
		$list = '';
		foreach ($this->commands as $type => $commands) {
			foreach ($commands as $key => $cmd) {
				$list .= "\n" . '§f- §e' . $cmd . "\n";
			}
		}
		return $list;
	}

	public function isNpc() : bool {
		return $this->isNpc;
	}

	public function setNpc(bool $value = true) : void {
		$this->isNpc = $value;
	}

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

	public function restorNpcData(CompoundTag $compoundTag) : void {
		$this->setNpc();
		$this->setName($compoundTag->getString(NpcDataIds::ENTITY_NAME));
		$this->setTaille($compoundTag->getFloat(NpcDataIds::ENTITY_SIZE));
		$this->setPitch($compoundTag->getFloat(NpcDataIds::ENTITY_PITCH));
		$this->setYaw($compoundTag->getFloat(NpcDataIds::ENTITY_YAW));
		$this->setCommands(unserialize($compoundTag->getString(NpcDataIds::ENTITY_COMMAND, 'a:0:{}')));
		$this->setEntityInventory(unserialize($compoundTag->getString(NpcDataIds::ENTITY_INVENTAIRE, 'a:0:{}')));
	}

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
