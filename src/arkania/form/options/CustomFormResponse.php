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

namespace arkania\form\options;

class CustomFormResponse {

	/** @var mixed[] */
	private $data;

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}

	public function getInt(string $name) : int {
		$this->checkExists($name);
		return $this->data[$name];
	}

	public function getString(string $name) : string {
		$this->checkExists($name);
		return $this->data[$name];
	}

	public function getFloat(string $name) : float {
		$this->checkExists($name);
		return $this->data[$name];
	}

	public function getBool(string $name) : bool {
		$this->checkExists($name);
		return $this->data[$name];
	}

	/**
	 * @return mixed[]
	 */
	public function getAll() : array {
		return $this->data;
	}

	private function checkExists(string $name) : void {
		if(!isset($this->data[$name])){
			throw new \InvalidArgumentException("Value \"$name\" not found");
		}
	}

}
