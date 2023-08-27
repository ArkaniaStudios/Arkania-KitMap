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

class AllyManager {

	private Faction $faction;

	public function __construct(
		Faction $faction
	) {
		$this->faction = $faction;
	}

	public function addAlly(Faction $faction) : void {
		$data = $this->faction->getFactionData();
		$data['allies'][] = $faction->getName();
		file_put_contents($this->faction->getConfig(), json_encode($data));
		$this->faction->ally[] = $faction->getName();
		$faction->ally[] = $this->faction->getName();
	}

	public function removeAlly(Faction $faction) : void {
		$data = $this->faction->getFactionData();
		$data['allies'] = array_diff($data['allies'], [$faction->getName()]);
		file_put_contents($this->faction->getConfig(), json_encode($data));
		$this->faction->ally = array_diff($this->faction->ally, [$faction->getName()]);
		$faction->ally = array_diff($faction->ally, [$this->faction->getName()]);
	}

	public function isAlly(?Faction $faction) : bool {
		if ($faction === null) return false;
		return in_array($faction->getName(), $this->faction->ally);
	}

	public function getAllies() : array {
		return $this->faction->ally;
	}

}
