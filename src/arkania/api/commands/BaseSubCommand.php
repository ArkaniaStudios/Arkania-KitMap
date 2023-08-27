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

namespace arkania\api\commands;

use arkania\api\commands\interface\ArgumentableInterface;
use arkania\utils\trait\ArgumentableTrait;
use arkania\utils\trait\ArgumentOrderException;
use pocketmine\command\CommandSender;

abstract class BaseSubCommand implements ArgumentableInterface {
	use ArgumentableTrait;

	private string $name;
	/** @var string[] */
	private array $aliases;

	private string $description;

	protected string $usageMessage;

	private ?string $permission = null;

	protected CommandSender $currentSender;

	protected BaseCommand $parent;

	/**
	 * @param string[] $aliases
	 * @throws ArgumentOrderException
	 */
	public function __construct(string $name, string $description = "", array $aliases = []) {
		$this->name = $name;
		$this->description = $description;
		$this->aliases = $aliases;
		foreach ($this->registerArguments() as $pos => $argument) {
			$this->registerArgument($pos, $argument);
		}
		$this->usageMessage = $this->generateUsageMessage();
	}

	/**
	 * @param (string|mixed)[] $args
	 */
	abstract public function onRun(CommandSender $player, array $args) : void;

	public function getName() : string {
		return $this->name;
	}

	/**
	 * @return string[]
	 */
	public function getAliases() : array {
		return $this->aliases;
	}

	public function getDescription() : string {
		return $this->description;
	}

	public function getUsageMessage() : string {
		return $this->usageMessage;
	}

	public function getPermission() : ?string {
		return $this->permission;
	}

	public function setPermission(string $permission) : void {
		$this->permission = $permission;
	}

	public function testPermissionSilent(CommandSender $sender) : bool {
		if(empty($this->permission)) {
			return true;
		}
		foreach(explode(";", $this->permission) as $permission) {
			if($sender->hasPermission($permission)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @internal Used to pass the current sender from the parent command
	 */
	public function setCurrentSender(CommandSender $currentSender) : void {
		$this->currentSender = $currentSender;
	}

	/**
	 * @internal Used to pass the parent context from the parent command
	 */
	public function setParent(BaseCommand $parent) : void {
		$this->parent = $parent;
	}

	public function sendUsage() : void {
		$this->currentSender->sendMessage("/{$this->parent->getName()} $this->usageMessage");
	}

}
