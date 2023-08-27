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

namespace arkania\titles;

use pocketmine\utils\SingletonTrait;

class TitleManager {
	use SingletonTrait;

	private TitleData $titleData;

	public function getTitles() : TitleData {
		if (isset($this->titleData)){
			return $this->titleData;
		}
		return $this->titleData = TitleData::create()
			->addTitle(
				Title::create()
					->setName('Noël')
					->setColor('§c')
			)
			->addTitle(
				Title::create()
					->setName('Halloween')
					->setColor('§6')
			)
			->addTitle(
				Title::create()
					->setName('Nouveau')
					->setColor('§a')
			);
	}

	public function getTitle(string $name) : ?Title {
		foreach ($this->getTitles()->getTitles() as $title) {
			if ($title->getName() === $name) {
				return $title;
			}
		}
		return null;
	}
}
