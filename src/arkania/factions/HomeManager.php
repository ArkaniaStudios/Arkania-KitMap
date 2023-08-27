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

namespace arkania\factions;

use arkania\Main;
use pocketmine\world\Position;
use Symfony\Component\Filesystem\Path;

class HomeManager {

	private Faction $faction;

	public function __construct(
		Faction $faction
	) {
		$this->faction = $faction;
	}

	public function setFactionHome(Position $position) : void {
		$this->removeFactionHome();
		$this->faction->addValueInData('homes', [
			$position->getX(),
			$position->getY(),
			$position->getZ(),
			$position->getWorld()->getFolderName()
		]);
	}

	public function removeFactionHome() : void {
		$this->faction->addValueInData('homes', []);
	}

	public function getFactionHome() : ?Position {
		$config = Path::join(Main::getInstance()->getDataFolder(), 'factions', $this->faction->getName() . '.json');
		$data = json_decode(file_get_contents($config), true);
		if (isset($data['homes'])) {
			$home = $data['homes'];
			if (count($home) > 0) {
				return new Position($home[0], $home[1], $home[2], Main::getInstance()->getServer()->getWorldManager()->getWorldByName($home[3]));
			}
		}
		return null;
	}

}
