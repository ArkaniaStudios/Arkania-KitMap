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

use arkania\Main;
use arkania\MainException;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\UpdateSoftEnumPacket;

class SoftEnumStore {

	/** @var CommandEnum[] */
	public static array $enums = [];

	public static function getEnumByName(string $name) : ?CommandEnum {
		return static::$enums[$name] ?? null;
	}

	/**
	 * @return CommandEnum[]
	 */
	public static function getEnums() : array {
		return static::$enums;
	}

	public static function addEnum(CommandEnum $enum) : void {
		static::$enums[$enum->getName()] = $enum;
		self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_ADD);
	}

	/**
	 * @param (string|mixed)[] $values
	 * @throws MainException
	 */
	public static function updateEnum(string $enumName, array $values) : void {
		if(self::getEnumByName($enumName) === null){
			throw new MainException("Unknown enum named " . $enumName);
		}
		$enum = self::$enums[$enumName] = new CommandEnum($enumName, $values);
		self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_SET);
	}

	/**
	 * @throws MainException
	 */
	public static function removeEnum(string $enumName) : void {
		if(($enum = self::getEnumByName($enumName)) === null){
			throw new MainException("Unknown enum named " . $enumName);
		}
		unset(static::$enums[$enumName]);
		self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_REMOVE);
	}

	public static function broadcastSoftEnum(CommandEnum $enum, int $type) : void {
		$pk = new UpdateSoftEnumPacket();
		$pk->enumName = $enum->getName();
		$pk->values = $enum->getValues();
		$pk->type = $type;
		foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $player) {
			$player->getNetworkSession()->sendDataPacket($pk);
		}
	}

}
