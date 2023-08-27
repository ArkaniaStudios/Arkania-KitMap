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

namespace arkania\api\commands\arguments;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

abstract class BaseArgument {

	protected string $name;
	protected bool $isOptional;
	protected CommandParameter $parameter;

	public function __construct(
		string $name,
		bool $isOptional = false
	) {
		$this->name = $name;
		$this->isOptional = $isOptional;

		$this->parameter = new CommandParameter();
		$this->parameter->paramName = $this->name;
		$this->parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID;
		$this->parameter->paramType |= $this->getNetworkType();
		$this->parameter->isOptional = $this->isOptional;
	}

	abstract public function getNetworkType() : int;

	abstract public function canParse(string $testString, CommandSender $sender) : bool;

	abstract public function parse(string $argument, CommandSender $sender) : mixed;

	public function getName() : string {
		return $this->name;
	}

	public function isOptional() : bool {
		return $this->isOptional;
	}

	public function getSpanLength() : int {
		return 1;
	}

	abstract public function getTypeName() : string;

	public final function getNetworkParameter() : CommandParameter {
		return $this->parameter;
	}

}
