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

use InvalidArgumentException;

abstract class BaseSelector extends CustomBaseFormElement {

	protected int $defaultOptionIndex;
	/** @var string[] */
	protected array $options;

	/**
	 * @param string[] $options
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct(string $name, string $text, array $options, int $defaultOptionIndex = 0) {
		parent::__construct($name, $text);
		$this->options = array_values($options);

		if(!isset($this->options[$defaultOptionIndex])){
			throw new InvalidArgumentException("No option at index $defaultOptionIndex, cannot set as default");
		}
		$this->defaultOptionIndex = $defaultOptionIndex;
	}

	public function validateValue(mixed $value) : void {
		if(!is_int($value)){
			throw new InvalidArgumentException("Expected int, got " . gettype($value));
		}
		if(!isset($this->options[$value])){
			throw new InvalidArgumentException("Option $value does not exist");
		}
	}

	/**
	 * Returns the text of the option at the specified index, or null if it doesn't exist.
	 */
	public function getOption(int $index) : ?string {
		return $this->options[$index] ?? null;
	}

	public function getDefaultOptionIndex() : int {
		return $this->defaultOptionIndex;
	}

	public function getDefaultOption() : string {
		return $this->options[$this->defaultOptionIndex];
	}

	/**
	 * @return string[]
	 */
	public function getOptions() : array {
		return $this->options;
	}

}
