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

namespace arkania\logs\async;

use pocketmine\scheduler\AsyncTask;

class SubmitMessageAsyncTask extends AsyncTask {

	/** @var callable */
	private $onRun;

	/** @var callable|null */
	private $onCompletion;

	public function __construct(
		callable $onRun,
		?callable $onCompletion = null
	) {
		$this->onRun = $onRun;
		$this->onCompletion = $onCompletion;
	}

	public function onRun() : void {
		($this->onRun)($this);
	}

	public function onCompletion() : void {
		if ($this->onCompletion !== null){
			($this->onCompletion)($this->getResult());
		}
	}

}
