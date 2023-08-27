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

namespace arkania\ranks\elements;

use arkania\Main;
use arkania\ranks\InvalidFormatException;

class RanksFormatInfo {

	private ?string $format = null;

	/**
	 * @throws InvalidFormatException
	 */
	public function __construct(
		string $format
	) {
		try {
			if (preg_match('/^[a-zA-Z0-9_\-»§ {}\[\]]$/', $format) || strlen($format) > 0){
				$this->format = $format;
			}else{
				throw new InvalidFormatException('Le format du rank est invalid il doit être compris entre les caractères suivant: a-zA-Z0-9_-»§ {}[]');
			}
		}catch (InvalidFormatException $exception) {
			Main::getInstance()->getLogger()->error($exception->getMessage());
			$this->format = null;
		}
	}

	public function getFormat() : ?string {
		return $this->format;
	}

}
