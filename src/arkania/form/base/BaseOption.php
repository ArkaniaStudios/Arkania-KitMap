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

namespace arkania\form\base;

use arkania\form\image\ButtonIcon;
use JsonSerializable;

class BaseOption implements JsonSerializable {

	private string $text;

	private ?ButtonIcon $image;

	public function __construct(
		string $text,
		?ButtonIcon $image = null
	) {
		$this->text = $text;
		$this->image = $image;
	}

	public function getText() : string {
		return $this->text;
	}

	public function getImage() : ?ButtonIcon {
		return $this->image;
	}

	/**
	 * @return (string|mixed)[]
	 */
	public function jsonSerialize() : array {
		$return = [
			'text' => $this->getText()
		];
		if ($this->getImage() !== null) {
			$return['image'] = $this->getImage();
		}
		return $return;
	}
}
