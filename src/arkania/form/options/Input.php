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

use arkania\form\base\CustomBaseFormElement;
use InvalidArgumentException;

class Input extends CustomBaseFormElement {

	private string $hint;

	private string $default;

	public function __construct(string $name, string $text, string $hintText = "", string $defaultText = "") {
		parent::__construct($name, $text);
		$this->hint = $hintText;
		$this->default = $defaultText;
	}

	public function getType() : string {
		return "input";
	}

	public function validateValue(mixed $value) : void {
		if(!is_string($value)){
			throw new InvalidArgumentException("Expected string, got " . gettype($value));
		}
	}

	/**
	 * Returns the text shown in the text-box when the box is not focused and there is no text in it.
	 */
	public function getHintText() : string {
		return $this->hint;
	}

	/**
	 * Returns the text which will be in the text-box by default.
	 */
	public function getDefaultText() : string {
		return $this->default;
	}

	protected function serializeElementData() : array {
		return [
			"placeholder" => $this->hint,
			"default" => $this->default
		];
	}

}
