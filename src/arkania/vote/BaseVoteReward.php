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

namespace arkania\vote;

use pocketmine\item\Item;

abstract class BaseVoteReward {

	/** @var Item[] */
	protected array $items = [];

	/** @var string[] */
	protected array $commands = [];

	/** @var bool[] */
	protected array $xp = [];

	private int $money;

	public function addItem(Item $item) : self {
		$this->items[] = $item;
		return $this;
	}

	/*
	 * 0 -> Player
	 * 1 -> Console
	 */
	public function addCommand(int $type, string $command) : self {
		$this->commands[$type] = $command;
		return $this;
	}

	public function addXp(int $xp, bool $level = false) : self {
		$this->xp[$xp] = $level;
		return $this;
	}

	public function addMoney(int $money) : self {
		$this->money = $money;
		return $this;
	}

	abstract public function giveReward() : void;

}
