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

use arkania\api\commands\SoftEnumStore;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

class SubArgument extends BaseArgument {

	public function __construct(string $name, bool $isOptional = false) {
		parent::__construct($name, $isOptional);

		$this->parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
		SoftEnumStore::addEnum($this->parameter->enum = new CommandEnum(strtolower($name), [strtolower($name)]));
	}

	public function getNetworkType() : int {
		return CommandParameter::FLAG_FORCE_COLLAPSE_ENUM;
	}

	public function getTypeName() : string {
		return 'mixed';
	}

	public function canParse(string $testString, CommandSender $sender) : bool {
		return true;
	}

	public function parse(mixed $argument, CommandSender $sender) : mixed {
		return $argument;
	}
}
