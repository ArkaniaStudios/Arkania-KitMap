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
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;

abstract class StringEnumArgument extends BaseArgument {

	/** @var (string|mixed)[] */
	protected array $value = [];

	public function __construct(string $name, bool $isOptional = false) {
		parent::__construct($name, $isOptional);
		$this->parameter->enum = new CommandEnum('', $this->getEnumValues());
	}

	public function getNetworkType() : int {
		return -1;
	}

	public function canParse(string $testString, CommandSender $sender) : bool {
		return (bool) preg_match(
			"/^(" . implode("|", array_map('\\strtolower', $this->getEnumValues())) . ")$/iu",
			$testString
		);
	}

	public function getValues(string $string) : mixed {
		return $this->value[strtolower($string)];
	}

	/**
	 * @return (string|mixed)[]
	 */
	public function getEnumValues() : array {
		return array_keys($this->value);
	}

}
