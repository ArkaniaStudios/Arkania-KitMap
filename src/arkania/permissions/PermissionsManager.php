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

namespace arkania\permissions;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\utils\SingletonTrait;
use ReflectionClass;
use ReflectionClassConstant;

class PermissionsManager {
	use SingletonTrait;

	public function __construct() {
		self::setInstance($this);
	}

	public function registerPermissionClass(object $class) : void {
		$reflection = new ReflectionClass($class);
		$constants = $reflection->getConstants(ReflectionClassConstant::IS_PUBLIC);
		$consoleRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_CONSOLE));
		$operatorRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_OPERATOR), [$consoleRoot]);
		$everyoneRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_USER), [$operatorRoot]);
		foreach ($constants as $constantName => $value) {
			if (str_contains($value, 'base')) {
				DefaultPermissions::registerPermission(new Permission($value), [$everyoneRoot]);
			}else{
				DefaultPermissions::registerPermission(new Permission($value), [$operatorRoot]);
			}
		}
	}
}
