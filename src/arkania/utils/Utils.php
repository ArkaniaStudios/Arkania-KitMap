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

namespace arkania\utils;

use arkania\Main;
use arkania\npc\base\CustomEntity;
use arkania\npc\base\SimpleEntity;
use arkania\player\CustomPlayer;
use pocketmine\block\Block;
use pocketmine\block\tile\Nameable;
use pocketmine\block\tile\Tile;
use pocketmine\block\tile\TileFactory;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class Utils {
	private static ?string $prefix;

	public static function setPrefix(string $prefix) : void {
		try {
			if (\preg_match('/^[a-zA-Z0-9 \[\]\-§»]{1,16}$/', $prefix)) {
				self::$prefix = $prefix;
			} else {
				throw new InvalidPrefixFormatException('§cLe préfixe doit contenir entre §e1 §cet §e16 §ccaractères respectant ce format: §e[a-zA-Z0-9§ []-»].');
			}
		} catch (InvalidPrefixFormatException $exception) {
			Main::getInstance()->getLogger()->error($exception->getMessage());
			self::$prefix = null;
		}
	}

	public static function getPrefix() : ?string {
		return self::$prefix;
	}

	public static function sendFakeBlock(CustomPlayer $player, Block $blocks, int $positionX, int $positionY, int $positionZ, ?string $customName = null, ?string $class = null) : void {
		$position = $player->getPosition();
		$position->x += $positionX;
		$position->y += $positionY;
		$position->z += $positionZ;
		$blockPosition = BlockPosition::fromVector3($position);
		$player->getNetworkSession()->sendDataPacket(UpdateBlockPacket::create(
			$blockPosition,
			TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId($blocks->getStateId()),
			UpdateBlockPacket::FLAG_NETWORK,
			UpdateBlockPacket::DATA_LAYER_NORMAL
		));
		if (!is_null($customName) && !is_null($class)){
			$player->getNetworkSession()->sendDataPacket(
				BlockActorDataPacket::create(
					$blockPosition,
					new CacheableNbt(
						CompoundTag::create()
							->setString(Tile::TAG_ID, TileFactory::getInstance()->getSaveId($class))
							->setString(Nameable::TAG_CUSTOM_NAME, $customName)
					)
				)
			);
		}
	}

	public static function isValidNumber(mixed $int) : bool {
		return is_numeric($int) && $int >= 0;
	}

	public static function removeColor(string $argument) : string {
		return preg_replace('/§[0-9a-fk-or]/i', '', $argument);
	}

	public static function getEntityById(Location $location, string|int $id) : SimpleEntity|CustomEntity|null {
		if(!isset(Loader::$entities[strtolower($id)])) {
			return null;
		}
		return new Loader::$entities[strtolower($id)]($location);

	}

}
