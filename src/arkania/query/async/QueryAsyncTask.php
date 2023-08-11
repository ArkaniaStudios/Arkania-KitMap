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

namespace arkania\query\async;

use pocketmine\scheduler\AsyncTask;

class QueryAsyncTask extends AsyncTask {
	/** @var callable */
	private $onRun;

	/** @var ?callable */
	private $onCompletion;

	public function __construct(
		callable $onRun,
		?callable $onCompletion,
		private readonly bool $useResult = true
	) {
		$this->onRun = $onRun;
		if (!\is_null($onCompletion)) {
			$this->onCompletion = $onCompletion;
		} else {
			$this->onCompletion = null;
		}
	}

	public function onRun() : void {
		$onRun = $this->onRun;
		if ($this->useResult) {
			$this->setResult($onRun());
		} else {
			$onRun();
		}
	}

	public function onCompletion() : void {
		if (!\is_null($this->onCompletion)) {
			$onCompletion = $this->onCompletion;
			$onCompletion($this->getResult());
		}
	}
}
