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

use JsonSerializable;

abstract class CustomBaseFormElement implements JsonSerializable {

	private string $name;
	private string $text;

	public function __construct(
		string $name,
		string $text
	) {
		$this->name = $name;
		$this->text = $text;
	}

	abstract public function getType() : string;

	public function getName() : string {
		return $this->name;
	}

	public function getText() : string {
		return $this->text;
	}

	abstract public function validateValue(mixed $value) : void;

	/**
	 * @return mixed[]
	 */
	final public function jsonSerialize() : array {
		$ret = $this->serializeElementData();
		$ret["type"] = $this->getType();
		$ret["text"] = $this->getText();
		return $ret;
	}

	/**
	 * @return mixed[]
	 */
	abstract protected function serializeElementData() : array;

}
