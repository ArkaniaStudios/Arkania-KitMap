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

namespace arkania\rankup;

class RankUpData {

	private string $name;
	private string $color;

	/** @var int[] */
	private array $nextStep;

	public static function create() : self {
		return new self();
	}

	public function setName(string $name) : self {
		$this->name = $name;
		return $this;
	}

	public function setColor(string $color) : self {
		$this->color = $color;
		return $this;
	}

	public function setNextStep(...$nextStep) : self {
		foreach ($nextStep as $step) {
			$this->nextStep[] = $step;
		}
		return $this;
	}

	public function getName() : string {
		return $this->name;
	}

	public function getColor() : string {
		return $this->color;
	}

	public function getNextStep() : array {
		return $this->nextStep;
	}

}
